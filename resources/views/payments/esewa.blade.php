<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Redirecting to eSewa</title>
</head>

<body>

    <div>

        <h2>Redirecting to eSewa...</h2>

        <p>
            Booking Number:
            {{ $booking->booking_number }}
        </p>

        <p>
            Amount:
            NPR {{ number_format($payment->amount, 2) }}
        </p>

        <form
            id="esewaForm"
            action="{{ config('services.esewa.payment_url') }}"
            method="POST"
        >

            <input
                type="hidden"
                name="amount"
                value="{{ $totalAmount }}"
            >

            <input
                type="hidden"
                name="tax_amount"
                value="0"
            >

            <input
                type="hidden"
                name="total_amount"
                value="{{ $totalAmount }}"
            >

            <input
                type="hidden"
                name="transaction_uuid"
                value="{{ $transactionUuid }}"
            >

            <input
                type="hidden"
                name="product_code"
                value="{{ config('services.esewa.merchant_code') }}"
            >

            <input
                type="hidden"
                name="product_service_charge"
                value="0"
            >

            <input
                type="hidden"
                name="product_delivery_charge"
                value="0"
            >

            <input
                type="hidden"
                name="success_url"
                value="{{ route('payments.esewa.success') }}"
            >

            <input
                type="hidden"
                name="failure_url"
                value="{{ route('payments.esewa.failure') }}"
            >

            <input
                type="hidden"
                name="signed_field_names"
                value="{{ $signedFieldNames }}"
            >

            <input
                type="hidden"
                name="signature"
                value="{{ $signature }}"
            >

            <button type="submit">
                Continue to eSewa
            </button>

        </form>

    </div>

</body>
</html>