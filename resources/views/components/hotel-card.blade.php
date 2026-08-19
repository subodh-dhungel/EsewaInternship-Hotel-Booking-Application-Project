<div class="hotel-card">

    {{-- Hotel Image --}}
    <div class="hotel-image">
        <img
            src="{{ asset('storage/' . $hotel->featured_image) }}"
            alt="{{ $hotel->name }}"
        >
    </div>

    <div class="hotel-body">

        {{-- Rating --}}
        <div class="rating">

            <div class="stars">

                <span class="stars-empty">
                    ★★★★★
                </span>

                <span
                    class="stars-filled"
                    style="width: {{ ($hotel->star_rating / 5) * 100 }}%;"
                >
                    ★★★★★
                </span>

            </div>

            <span class="rating-number">
                {{ number_format($hotel->star_rating, 1) }}
            </span>

        </div>


        {{-- Hotel Name --}}
        <h3>
            {{ $hotel->name }}
        </h3>


        {{-- Location --}}
        <p class="hotel-location">
            {{ $hotel->city }}, {{ $hotel->district }}
        </p>


        {{-- Amenities --}}
        <p class="amenities">
            {{ $hotel->amenity->pluck('name')->implode(' • ') }}
        </p>


        {{-- Find Cheapest Room --}}
        @php
            $room = $hotel->roomTypes
                ->sortBy(function ($room) {
                    return $room->discount_price ?? $room->price;
                })
                ->first();
        @endphp


        {{-- Price --}}
        <div class="price-row">

            @if ($room)

                <strong>
                    रु {{ number_format($room->discount_price ?? $room->price, 2) }}
                </strong>

                <span>
                    / night
                </span>

            @else

                <strong>
                    Price unavailable
                </strong>

            @endif

        </div>


        {{-- Customer Action --}}
        <div class="hotel-actions">

            <a
                href="{{ route('hotels.show', $hotel->id) }}"
                class="btn"
            >
                View Hotel
            </a>

        </div>

    </div>

</div>