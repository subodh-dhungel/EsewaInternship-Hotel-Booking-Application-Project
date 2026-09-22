<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Continue to eSewa | Esewa Hotels</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="esewa-transfer-page">
    <main class="esewa-transfer" aria-labelledby="payment-title">
        <header class="esewa-transfer__header">
            <a href="{{ route('hotels.featured') }}" aria-label="Return to Esewa Hotels">
                <img src="https://esewahotels.com/images/esewa_hotel_logo_white.svg" alt="eSewa Hotels">
            </a>
            <span class="esewa-transfer__secure"><span aria-hidden="true">✓</span> Secure checkout</span>
        </header>

        <section class="esewa-transfer__content">
            <div class="esewa-transfer__brand"><span class="esewa-transfer__brand-mark">e</span><span>Powered by <strong>eSewa</strong></span></div>
            <div class="esewa-transfer__loader" aria-hidden="true"><span></span><span></span><span></span></div>
            <h1 id="payment-title">Taking you to eSewa</h1>
            <p class="esewa-transfer__lead">Your booking is ready. Continue to eSewa to complete your secure payment.</p>

            <dl class="esewa-transfer__summary">
                <div><dt>Booking number</dt><dd>{{ $booking->booking_number }}</dd></div>
                <div><dt>Amount to pay</dt><dd>NPR {{ number_format($payment->amount, 2) }}</dd></div>
                <div><dt>Payment method</dt><dd>eSewa wallet</dd></div>
            </dl>

            <form id="esewaForm" action="{{ config('services.esewa.payment_url') }}" method="POST">
                <input type="hidden" name="amount" value="{{ $totalAmount }}">
                <input type="hidden" name="tax_amount" value="0">
                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                <input type="hidden" name="transaction_uuid" value="{{ $transactionUuid }}">
                <input type="hidden" name="product_code" value="{{ $productCode }}">
                <input type="hidden" name="product_service_charge" value="0">
                <input type="hidden" name="product_delivery_charge" value="0">
                <input type="hidden" name="success_url" value="{{ route('payments.esewa.success') }}">
                <input type="hidden" name="failure_url" value="{{ route('payments.esewa.failure') }}">
                <input type="hidden" name="signed_field_names" value="{{ $signedFieldNames }}">
                <input type="hidden" name="signature" value="{{ $signature }}">
                <button class="esewa-transfer__button" type="submit">Continue to eSewa <span aria-hidden="true">→</span></button>
            </form>
            <p class="esewa-transfer__note">Do not close this window while we redirect you. You will return here after payment.</p>
        </section>

        <footer class="esewa-transfer__footer"><span>Esewa Hotels</span><span>Secure payment handoff</span></footer>
    </main>
    <script>
        window.setTimeout(function () {
            document.getElementById('esewaForm').submit();
        }, 900);
    </script>
</body>
</html>
