<x-layout>

    <main class="page-container">
        @php
            $activeBookings = $bookings->whereIn('booking_status', ['pending', 'confirmed'])->count();
            $completedBookings = $bookings->where('booking_status', 'completed')->count();
        @endphp

        <div class="bookings-page-header">
            <div>
                <span class="section-kicker">Your travel dashboard</span>
                <h1>My bookings</h1>
                <p>Keep track of upcoming stays, payments and booking details.</p>
            </div>
            <a href="{{ route('hotels.index') }}" class="btn">Find another stay <span aria-hidden="true">→</span></a>
        </div>

        <div class="booking-summary">
            <div><span class="booking-summary__icon">▣</span><div><strong>{{ $bookings->count() }}</strong><small>Total bookings</small></div></div>
            <div><span class="booking-summary__icon booking-summary__icon--green">✓</span><div><strong>{{ $activeBookings }}</strong><small>Upcoming stays</small></div></div>
            <div><span class="booking-summary__icon booking-summary__icon--gold">↗</span><div><strong>{{ $completedBookings }}</strong><small>Completed stays</small></div></div>
        </div>

        <section class="details-section">
            @if ($bookings->isEmpty())
                <div class="booking-empty-state">
                    <div class="booking-empty-state__icon">⌂</div>
                    <h2>Your next stay starts here</h2>
                    <p>You have not booked a hotel yet. Explore comfortable stays across Nepal.</p>
                    <a href="{{ route('hotels.index') }}" class="btn">Explore hotels <span aria-hidden="true">→</span></a>
                </div>
            @else
                <div class="bookings-grid">
                    @foreach ($bookings as $booking)
                        <article class="booking-card">
                            <div class="booking-header">
                                <div>
                                    <span class="booking-card__eyebrow">{{ $booking->booking_number }}</span>
                                    <h3>{{ $booking->hotel->name }}</h3>
                                    <p class="booking-location">{{ $booking->hotel->city?->name ?? 'Nepal' }}{{ $booking->hotel->district ? ', ' . $booking->hotel->district : '' }}</p>
                                </div>
                                <span class="booking-status booking-status-{{ $booking->booking_status }}">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                            </div>
                            <div class="booking-stay-bar">
                                <div><span>Stay dates</span><strong>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</strong></div>
                                <div><span>Room</span><strong>{{ $booking->roomType->name }}</strong></div>
                                <div><span>Guests</span><strong>{{ $booking->adults }} adults{{ $booking->children ? ', ' . $booking->children . ' children' : '' }}</strong></div>
                            </div>
                            <div class="booking-details">
                                <div class="booking-detail">
                                    <span class="detail-label">Rooms</span><span class="detail-value">{{ $booking->number_of_rooms }}</span>
                                </div>
                                <div class="booking-detail">
                                    <span class="detail-label">Payment</span><span class="payment-status payment-status-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span>
                                </div>
                            </div>
                            <div class="booking-footer">
                                <div><span class="detail-label">Total price</span><strong class="booking-price">रु {{ number_format($booking->total_price, 2) }}</strong></div>
                                <div class="booking-actions">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">View details <span aria-hidden="true">→</span></a>
                                    @if ($booking->payment_status === 'pending')
                                        <a href="{{ route('payments.initiate', $booking) }}" class="btn">Continue payment</a>
                                    @endif
                                    @if (in_array($booking->booking_status, ['pending', 'confirmed']))
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="booking-cancel">Cancel</button></form>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

    </main>

</x-layout>
