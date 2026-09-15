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
        // Make sure the booking belongs to the logged-in user.
        abort_unless(
            $booking->user_id === Auth::id(),
            403
        );

        // Payment can only be started for a pending booking.
        abort_unless(
            $booking->payment_status === 'pending',
            400,
            'This booking cannot be paid.'
        );

        // Do not allow payment after the booking has expired.
        if (
            $booking->expires_at &&
            $booking->expires_at->isPast()
        ) {
            abort(400, 'This booking has expired.');
        }

        /*
         * Reuse an existing pending payment if one exists.
         *
         * This prevents the same booking from creating
         * multiple active payment attempts unnecessarily.
         */
        $payment = Payment::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$payment) {
            $transactionUuid = Str::uuid()->toString();

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $transactionUuid,
                'payment_method' => 'esewa',
                'amount' => $booking->total_price,
                'status' => 'pending',
            ]);
        }

        /*
         * IMPORTANT:
         * The amount comes from our database.
         * We never accept the payment amount from the browser.
         */
        
        $totalAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

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
         * Compare the amount received from eSewa
         * with the amount stored in OUR database.
         */
        $databaseAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

        $responseAmount = number_format(
            (float) $response['total_amount'],
            2,
            '.',
            ''
        );

        abort_unless(
            $databaseAmount === $responseAmount,
            400,
            'Payment amount mismatch.'
        );

        /*
         * Prevent the same successful payment from
         * being processed repeatedly.
         */
        if ($payment->status === 'success') {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'success',
                    'Payment was already processed.'
                );
        }

        /*
         * Atomically update both payment and booking.
         *
         * Either both updates succeed or neither does.
         */
        DB::transaction(function () use (
            $payment,
            $response
        ) {
            /*
             * Lock the payment row so two requests
             * cannot process the same payment simultaneously.
             */
            $payment = Payment::where(
                'id',
                $payment->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Check again after acquiring the lock.
             */
            if ($payment->status === 'success') {
                return;
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
            $payment->booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);
        });

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

        /*
         * Do not overwrite an already successful payment.
         */
        if ($payment->status === 'success') {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'success',
                    'Payment was already completed.'
                );
        }

        /*
         * Mark this payment attempt as failed.
         */
        $payment->update([
            'status' => 'failed',
        ]);

        /*
         * Only change the booking if it has not already
         * been successfully paid.
         */
        if ($payment->booking->payment_status !== 'paid') {
            $payment->booking->update([
                'payment_status' => 'failed',
            ]);
        }

        return redirect()
            ->route('bookings.history')
            ->with(
                'error',
                'Payment failed. Your booking was not confirmed.'
            );
    }
}
