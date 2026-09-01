<x-layout>

    <main class="page-container">

        {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}

        <div class="page-header">

            <div>

                <h1>My Bookings</h1>

                <p>
                    View your hotel bookings and stay information.
                </p>

            </div>

        </div>


        {{-- =====================================================
         BOOKING HISTORY
         ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Booking History</h2>

                    <p>
                        Here you can see all the hotels and rooms you have booked.
                    </p>

                </div>

            </div>


            {{-- =================================================
             NO BOOKINGS
             ================================================= --}}

            @if ($bookings->isEmpty())

                <div class="empty-state card">

                    <h3>No bookings yet</h3>

                    <p>
                        You have not booked any hotels yet.
                    </p>

                    <a href="{{ route('hotels.index') }}" class="btn btn-primary">
                        Explore Hotels
                    </a>

                </div>

            @else

                {{-- =================================================
                 BOOKINGS
                 ================================================= --}}

                <div class="bookings-grid">

                    @foreach ($bookings as $booking)

                        <div class="card booking-card">

                            {{-- Booking Header --}}

                            <div class="booking-header">

                                <div>

                                    <h3>
                                        {{ $booking->hotel->name }}
                                    </h3>

                                    <p class="booking-location">
                                        {{ $booking->hotel->city }},
                                        {{ $booking->hotel->district }},
                                        {{ $booking->hotel->country }}
                                    </p>

                                </div>


                                {{-- Booking Status --}}

                                <span class="booking-status booking-status-{{ $booking->booking_status }}">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>

                            </div>


                            {{-- Booking Number --}}

                            <div class="booking-number">

                                <span class="detail-label">
                                    Booking Number
                                </span>

                                <span class="detail-value">
                                    {{ $booking->booking_number }}
                                </span>

                            </div>


                            {{-- Booking Information --}}

                            <div class="booking-details">

                                {{-- Room Type --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Room Type
                                    </span>

                                    <span class="detail-value">
                                        {{ $booking->roomType->name }}
                                    </span>

                                </div>


                                {{-- Number of Rooms --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Rooms
                                    </span>

                                    <span class="detail-value">
                                        {{ $booking->number_of_rooms }}
                                    </span>

                                </div>


                                {{-- Check-in --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Check-in
                                    </span>

                                    <span class="detail-value">
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                                    </span>

                                </div>


                                {{-- Check-out --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Check-out
                                    </span>

                                    <span class="detail-value">
                                        {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                                    </span>

                                </div>


                                {{-- Adults --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Adults
                                    </span>

                                    <span class="detail-value">
                                        {{ $booking->adults }}
                                    </span>

                                </div>


                                {{-- Children --}}

                                <div class="booking-detail">

                                    <span class="detail-label">
                                        Children
                                    </span>

                                    <span class="detail-value">
                                        {{ $booking->children }}
                                    </span>

                                </div>

                            </div>


                            {{-- Price and Payment --}}

                            <div class="booking-footer">

                                <div>

                                    <span class="detail-label">
                                        Total Price
                                    </span>

                                    <span class="booking-price">
                                        Rs. {{ number_format($booking->total_price, 2) }}
                                    </span>

                                </div>


                                <div>

                                    <span class="detail-label">
                                        Payment
                                    </span>

                                    <span class="payment-status payment-status-{{ $booking->payment_status }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>

                                </div>

                            </div>


                            {{-- Actions --}}

                            <div class="booking-actions">

                                <a href="{{ route('bookings.history', $booking) }}" class="btn">
                                    View Booking
                                </a>

                                @if ($booking->payment_status === 'pending')

                                    <a href="#" class="btn btn-primary">
                                        Continue Payment
                                    </a>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </section>

    </main>

</x-layout>
