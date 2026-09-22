<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initiate(Booking $booking)
    {
        $payment = DB::transaction(function () use ($booking) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($lockedBooking->user_id === Auth::id(), 403);
            abort_unless(
                $lockedBooking->payment_status === 'pending',
                400,
                'This booking cannot be paid.'
            );

            if ($lockedBooking->expires_at && $lockedBooking->expires_at->isPast()) {
                abort(400, 'This booking has expired.');
            }

            $payment = Payment::where('booking_id', $lockedBooking->id)
                ->where('status', 'pending')
                ->latest('id')
                ->first();

            if ($payment) {
                return $payment;
            }

            return Payment::create([
                'booking_id' => $lockedBooking->id,
                'transaction_id' => Str::uuid()->toString(),
                'payment_method' => 'esewa',
                'amount' => $lockedBooking->total_price,
                'status' => 'pending',
            ]);
        });

        /*
         * IMPORTANT:
         * The amount comes from our database.
         * We never accept the payment amount from the browser.
         */
        
        $totalAmount = $this->normalizeAmount($payment->amount);

        $productCode = config('services.esewa.merchant_code');
        $secretKey = config('services.esewa.secret_key');

        abort_unless(
            $productCode && $secretKey,
            500,
            'eSewa payment configuration is missing.'
        );

        /*
         * eSewa requires these fields to be signed
         * in this exact order.
         */
        $signedFieldNames =
            'total_amount,transaction_uuid,product_code';

        $message =
            "total_amount={$totalAmount}," .
            "transaction_uuid={$payment->transaction_id}," .
            "product_code={$productCode}";

        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $message,
                $secretKey,
                true
            )
        );

        return view('payments.esewa', [
            'booking' => $booking,
            'payment' => $payment,
            'transactionUuid' => $payment->transaction_id,
            'totalAmount' => $totalAmount,
            'signedFieldNames' => $signedFieldNames,
            'signature' => $signature,
            'productCode' => $productCode,
        ]);
    }

    public function success()
    {
        $encodedResponse = request()->query('data');

        if (!$encodedResponse) {
            abort(400, 'Invalid payment response.');
        }

        /*
         * Decode eSewa's Base64 response.
         */
        $decodedResponse = base64_decode(
            $encodedResponse,
            true
        );

        if ($decodedResponse === false) {
            abort(400, 'Invalid payment response.');
        }

        /*
         * Convert JSON response into a PHP array.
         */
        $response = json_decode(
            $decodedResponse,
            true
        );

        if (!is_array($response)) {
            abort(400, 'Invalid payment response.');
        }

        /*
         * Make sure all fields required for our
         * verification are present.
         */
        $requiredFields = [
            'status',
            'signature',
            'transaction_uuid',
            'transaction_code',
            'total_amount',
            'product_code',
            'signed_field_names',
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $response)) {
                abort(400, 'Incomplete payment response.');
            }
        }

        /*
         * We only accept COMPLETE payments.
         */
        abort_unless(
            $response['status'] === 'COMPLETE',
            400,
            'Payment was not completed.'
        );

        /*
         * Find the payment that OUR application created.
         *
         * transaction_uuid comes from eSewa's response,
         * but it is only used to locate our own database record.
         */
        $payment = Payment::where(
            'transaction_id',
            $response['transaction_uuid']
        )->first();

        if (!$payment) {
            abort(404, 'Payment record not found.');
        }

        /*
         * Make sure this payment belongs to our merchant account.
         */
        abort_unless(
            $response['product_code'] ===
                config('services.esewa.merchant_code'),
            400,
            'Invalid product code.'
        );

        /*
         * eSewa's documented response contains:
         *
         * transaction_code,
         * status,
         * total_amount,
         * transaction_uuid,
         * product_code,
         * signed_field_names
         *
         * We only accept the expected signed fields.
         */
        $expectedSignedFields = [
            'transaction_code',
            'status',
            'total_amount',
            'transaction_uuid',
            'product_code',
            'signed_field_names',
        ];

        $signedFields = explode(
            ',',
            $response['signed_field_names']
        );

        abort_unless(
            $signedFields === $expectedSignedFields,
            400,
            'Invalid signed fields.'
        );

        /*
         * Rebuild exactly the same message that eSewa signed.
         */
        $messageParts = [];

        foreach ($signedFields as $field) {
            if (!array_key_exists($field, $response)) {
                abort(400, 'Invalid signed response.');
            }

            $messageParts[] =
                $field . '=' . $response[$field];
        }

        $message = implode(
            ',',
            $messageParts
        );

        /*
         * Generate our own signature using our secret key.
         */
        $expectedSignature = base64_encode(
            hash_hmac(
                'sha256',
                $message,
                config('services.esewa.secret_key'),
                true
            )
        );

        /*
         * Compare signatures safely.
         */
        if (
            !hash_equals(
                $expectedSignature,
                $response['signature']
            )
        ) {
            abort(400, 'Invalid payment signature.');
        }

        /*
         * Atomically update both payment and booking.
         *
         * Either both updates succeed or neither does.
         */
        $wasAlreadyProcessed = DB::transaction(function () use ($payment, $response) {
            /*
             * Lock the payment row so two requests
             * cannot process the same payment simultaneously.
             */
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $booking = Booking::whereKey($payment->booking_id)
                ->lockForUpdate()
                ->firstOrFail();

            $databaseAmount = $this->normalizeAmount($payment->amount);
            $responseAmount = $this->normalizeAmount($response['total_amount']);

            abort_unless($databaseAmount === $responseAmount, 400, 'Payment amount mismatch.');

            /*
             * Check again after acquiring the lock.
             */
            if ($payment->status === 'success' || $booking->payment_status === 'paid') {
                return true;
            }

            /*
             * Update payment.
             */
            $payment->update([
                'status' => 'success',
                'transaction_code' =>
                    $response['transaction_code'],
                'paid_at' => now(),
            ]);

            /*
             * Update the associated booking.
             */
            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);

            return false;
        });

        if ($wasAlreadyProcessed) {
            return redirect()
                ->route('bookings.history')
                ->with('success', 'Payment was already processed.');
        }

        return redirect()
            ->route('bookings.history')
            ->with(
                'success',
                'Payment successful. Your booking is confirmed.'
            );
    }

    public function failure()
    {
        $transactionUuid = request()->query(
            'transaction_uuid'
        );

        if (!$transactionUuid) {
            abort(400, 'Invalid payment response.');
        }

        /*
         * Find the payment created by our application.
         */
        $payment = Payment::where(
            'transaction_id',
            $transactionUuid
        )->first();

        if (!$payment) {
            abort(404, 'Payment record not found.');
        }

        abort_unless($payment->booking->user_id === Auth::id(), 403);

        $alreadyPaid = DB::transaction(function () use ($payment) {
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();
            $booking = Booking::whereKey($payment->booking_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === 'success' || $booking->payment_status === 'paid') {
                return true;
            }

            $payment->update(['status' => 'failed']);
            $booking->update(['payment_status' => 'failed']);

            return false;
        });

        if ($alreadyPaid) {
            return redirect()
                ->route('bookings.history')
                ->with('success', 'Payment was already completed.');
        }

        return redirect()
            ->route('bookings.history')
            ->with(
                'error',
                'Payment failed. Your booking was not confirmed.'
            );
    }

    private function normalizeAmount(mixed $amount): string
    {
        $amount = (string) $amount;

        if (! preg_match('/^\d+(?:\.\d{1,2})?$/', $amount)) {
            abort(400, 'Invalid payment amount.');
        }

        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '');

        return (ltrim($whole, '0') ?: '0')
            . '.'
            . str_pad($fraction, 2, '0');
    }
}
