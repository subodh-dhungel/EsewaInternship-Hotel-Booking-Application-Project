<x-layout>

    <main>
        <h1>List of hotels</h1>
        <div class="hotel-grid">
            @foreach ($hotels as $hotel)
                @if ($hotels->isEmpty())
                    <p>There are no hotels available</p>
                @else
                    <div class="hotel-card">
                        <img src="{{$hotel->featured_image}}" alt="">
                        <div class="hotel-body">
                            <div class="rating">
                                <div class="stars"> <span class="stars-empty">★★★★★</span> <span class="stars-filled"
                                        style="width: {{ ($hotel->star_rating / 5) * 100 }}%;"> ★★★★★ </span> </div>
                                <span class="rating-number"> {{ number_format($hotel->star_rating, 1) }} </span>
                            </div>
                            <h3>{{ $hotel->name }}</h3>
                            <p class="amenities"> {{ $hotel->amenity->pluck('name')->implode(' • ') }} </p>
                            <div class="price-row"> @php
                                $room = $hotel->roomTypes
                                    ->sortBy(function ($room) {
                                        return $room->discount_price ?? $room->price;
                                    })
                                    ->first();
                            @endphp @if ($room)
                                    <div class="price-row">
                                        @php
                                            $room = $hotel->roomTypes
                                                ->sortBy(function ($room) {
                                                    return $room->discount_price ?? $room->price;
                                                })
                                                ->first();
                                        @endphp

                                        @if ($room)
                                            <strong>
                                                रु {{ number_format($room->discount_price ?? $room->price, 2) }}
                                            </strong>

                                            <span>/ night</span>
                                        @else
                                            <strong>Price unavailable</strong>
                                        @endif
                                    </div>
                                @else
                                    <strong> Price unavailable </strong>
                                @endif <a href="/hotels/{{ $hotel->id }}" class="btn"> View
                                </a> </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </main>
</x-layout>
