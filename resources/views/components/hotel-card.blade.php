<div class="hotel-card">

    {{-- Hotel Image --}}
    <img
        src="{{ asset('storage/' . $hotel->featured_image) }}"
        alt="{{ $hotel->name }}"
    >

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


        {{-- Amenities --}}
        <p class="amenities">
            {{ $hotel->amenity->pluck('name')->implode(' • ') }}
        </p>


        {{-- Cheapest Room --}}
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


        {{-- Actions --}}
        <div class="hotel-actions">

            {{-- View --}}
            <a
                href="{{ route('hotels.show', $hotel->id) }}"
                class="btn"
            >
                View
            </a>


            {{-- Customer --}}
            @if ($status === 'customer')

                {{-- No additional actions for customers --}}


            {{-- Active Hotel --}}
            @elseif ($status === 'active')

                <a
                    href="{{ route('hotel.edit', $hotel->id) }}"
                    class="btn"
                >
                    Edit
                </a>

                <form
                    action="{{ route('hotel.deactivate', $hotel->id) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="btn-alert"
                    >
                        Deactivate
                    </button>

                </form>


            {{-- Pending Hotel --}}
            @elseif ($status === 'pending')

                <a
                    href="{{ route('hotel.edit', $hotel->id) }}"
                    class="btn"
                >
                    Edit
                </a>

                <span class="hotel-status">
                    Pending Approval
                </span>


            {{-- Inactive Hotel --}}
            @elseif ($status === 'inactive')

                <a
                    href="{{ route('hotel.edit', $hotel->id) }}"
                    class="btn"
                >
                    Edit
                </a>

                <form
                    action="{{ route('hotel.activate', $hotel->id) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="btn"
                    >
                        Activate
                    </button>

                </form>

            @endif

        </div>

    </div>

</div>