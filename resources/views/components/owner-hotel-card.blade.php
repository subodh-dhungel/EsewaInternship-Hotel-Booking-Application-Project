<div class="hotel-card">

    {{-- Hotel Image --}}
    <div class="hotel-image">

        @if ($hotel->featured_image)

            <img
                src="{{ asset('storage/' . $hotel->featured_image) }}"
                alt="{{ $hotel->name }}"
            >

        @else

            <img
                src="https://via.placeholder.com/600x400?text=No+Image"
                alt="No hotel image"
            >

        @endif

    </div>


    <div class="hotel-body">

        {{-- Status --}}
        <div class="hotel-status">
            {{ ucfirst($hotel->status) }}
        </div>


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

            @if ($hotel->amenity->isNotEmpty())

                {{ $hotel->amenity->pluck('name')->implode(' • ') }}

            @else

                No amenities listed

            @endif

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


        {{-- Owner Actions --}}
        <div class="hotel-actions">

            {{-- View --}}
            <a
                href="{{ route('owner.hotels.show', $hotel->id) }}"
                class="btn"
            >
                View
            </a>


            {{-- Edit --}}
            <a
                href="{{ route('owner.hotels.edit', $hotel->id) }}"
                class="btn btn-secondary"
            >
                Edit
            </a>


            {{-- Active --}}
            @if ($hotel->status === 'active')

                <form
                    action="{{ route('owner.hotels.deactivate', $hotel->id) }}"
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


            {{-- Pending --}}
            @elseif ($hotel->status === 'pending')

                <span class="hotel-status">
                    Pending Approval
                </span>


            {{-- Inactive --}}
            @elseif ($hotel->status === 'inactive')

                <form
                    action="{{ route('owner.hotels.activate', $hotel->id) }}"
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