<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initiate(Booking $booking)
    {
        // user ra user ko booking id match cha ki chaina
        abort_unless(
            $booking->user_id === Auth::id(),
            403
        );

        // payment can only be initiated for a pending booking
        abort_unless(
            $booking->payment_status === 'pending',
            400
        );

        // booking expired vako cha ki chaina check garna lai
        if (
            $booking->expires_at &&
            $booking->expires_at->isPast()
        ) {
            abort(400, 'This booking has expired.');
        }

        // Generate a unique transaction UUID
        $transactionUuid = Str::uuid()->toString();

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'transaction_id' => $transactionUuid,
            'payment_method' => 'esewa',
            'amount' => $booking->total_price,
            'status' => 'pending'
        ]);

        // eSewa signature data

        $totalAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

        $productCode = config('services.esewa.merchant_code');

        $signedFieldNames = 'total_amount,transaction_uuid,product_code';

        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";

        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $message,
                config('services.esewa.secret_key'),
                true
            )
        );

        // dd([
        //     'merchant_code' => $productCode,
        //     'secret_loaded' => !empty(config('services.esewa.secret_key')),
        //     'total_amount' => $totalAmount,
        //     'transaction_uuid' => $transactionUuid,
        //     'signed_field_names' => $signedFieldNames,
        //     'message' => $message,
        //     'signature' => $signature,
        // ]);

        return view('payments.esewa', [
            'booking' => $booking,
            'payment' => $payment,
            'transactionUuid' => $transactionUuid,
            'totalAmount' => $totalAmount,
            'signedFieldNames' => $signedFieldNames,
            'signature' => $signature,
        ]);
    }

    public function success()
    {
        $encodedResponse = request()->query('data');

        if (!$encodedResponse) {
            abort(400, 'Invalid payment response.');
        }

        $decodedResponse = base64_decode($encodedResponse, true);

        if ($decodedResponse === false) {
            abort(400, 'Invalid payment response.');
        }

        $response = json_decode($decodedResponse, true);

        if (!is_array($response)) {
            abort(400, 'Invalid payment response.');
        }

        // Make sure eSewa says the payment is complete
        if (($response['status'] ?? null) !== 'COMPLETE') {
            abort(400, 'Payment was not completed.');
        }

        // Check required response fields
        if (
            !isset(
                $response['transaction_uuid'],
                $response['transaction_code'],
                $response['total_amount'],
                $response['product_code'],
                $response['signature'],
                $response['signed_field_names']
            )
        ) {
            abort(400, 'Incomplete payment response.');
        }

        // Find our payment using the transaction UUID
        $payment = Payment::where(
            'transaction_id',
            $response['transaction_uuid']
        )->first();

        if (!$payment) {
            abort(404, 'Payment record not found.');
        }

        // Build the message using eSewa's signed fields
        $messageParts = [];

        foreach (
            explode(',', $response['signed_field_names'])
            as $field
        ) {
            if (!array_key_exists($field, $response)) {
                abort(400, 'Invalid signed response.');
            }

            $messageParts[] = $field . '=' . $response[$field];
        }

        $message = implode(',', $messageParts);

        // Generate our own signature
        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $message,
                config('services.esewa.secret_key'),
                true
            )
        );

        // Compare our signature with eSewa's signature
        if (!hash_equals($signature, $response['signature'])) {
            abort(400, 'Invalid payment signature.');
        }

        // Verify payment amount
        if (
            number_format((float) $payment->amount, 2, '.', '') !==
            number_format((float) $response['total_amount'], 2, '.', '')
        ) {
            abort(400, 'Payment amount mismatch.');
        }

        // Update payment
        $payment->update([
            'status' => 'success',
            'transaction_code' => $response['transaction_code'],
            'paid_at' => now(),
        ]);

        // Update booking
        $payment->booking->update([
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        return redirect()
            ->route('bookings.history')
            ->with(
                'success',
                'Payment successful. Your booking is confirmed.'
            );
    }


    public function failure()
    {
        $transactionUuid = request()->query('transaction_uuid');

        if (!$transactionUuid) {
            abort(400, 'Invalid payment response.');
        }

        // Find the payment created before sending the customer to eSewa
        $payment = Payment::where(
            'transaction_id',
            $transactionUuid
        )->first();

        if (!$payment) {
            abort(404, 'Payment record not found.');
        }

        // Mark payment as failed
        $payment->update([
            'status' => 'failed',
        ]);

        // Mark booking payment as failed
        $payment->booking->update([
            'payment_status' => 'failed',
        ]);

        return redirect()
            ->route('bookings.history')
            ->with(
                'error',
                'Payment failed. Your booking was not confirmed.'
            );
    }
}
