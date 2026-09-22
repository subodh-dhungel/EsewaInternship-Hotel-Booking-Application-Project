@php
    $room = $hotel->roomTypes
        ->sortBy(fn ($room) => $room->discount_price ?? $room->price)
        ->first();
@endphp

<article class="hotel-card hotel-card--result">
    <a class="hotel-image" href="{{ route('hotels.show', $hotel->id) }}">
        <img src="{{ asset('storage/' . $hotel->featured_image) }}" alt="{{ $hotel->name }}">
        <span class="hotel-card-badge">Available to book</span>
    </a>

    <div class="hotel-body">
        <div class="hotel-card-topline">
            <span class="hotel-location">{{ $hotel->city?->name ?? 'Nepal' }}{{ $hotel->city?->country ? ', ' . $hotel->city->country : '' }}</span>
            <span class="hotel-rating">★ {{ number_format($hotel->star_rating, 1) }}</span>
        </div>

        <h3><a href="{{ route('hotels.show', $hotel->id) }}">{{ $hotel->name }}</a></h3>
        <p class="amenities">{{ $hotel->amenity->pluck('name')->take(3)->implode(' · ') ?: 'Comfortable stay with thoughtful amenities' }}</p>

        <div class="hotel-card-footer">
            <div>
                @if ($room)
                    <strong class="hotel-price">रु {{ number_format($room->discount_price ?? $room->price, 0) }}</strong>
                    <small>/ night</small>
                @else
                    <strong class="hotel-price">Price unavailable</strong>
                @endif
            </div>
            <a class="hotel-card-link" href="{{ route('hotels.show', $hotel->id) }}">View hotel <span aria-hidden="true">→</span></a>
        </div>
    </div>
</article>
