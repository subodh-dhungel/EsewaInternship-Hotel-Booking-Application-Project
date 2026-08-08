<x-layout>
    <section class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Find Your Perfect Stay</h1>
            <p>Book luxury hotels, resorts and budget rooms across Nepal.</p>

            <form action="/hotels/search" method="GET" class="search-card">
                <input type="text" name="destination" placeholder="Destination">
                <input type="date" name="checkin">
                <input type="date" name="checkout">
                <select class="select_options" name="guests">
                    <option>1 Guest</option>
                    <option>2 Guests</option>
                    <option>3 Guests</option>
                    <option>4+ Guests</option>
                </select>
                <button type="submit">Search Hotels</button>
            </form>
        </div>
    </section>

    <section class="section container">
        <h2>Popular Destinations</h2>

        <div class="destinations">
            <div class="destination-card">
                <h3><b>Kathmandu</b></h3>
                <p>240 Hotels</p>
            </div>
            <div class="destination-card">
                <h3><b>Pokhara</b></h3>
                <p>180 Hotels</p>
            </div>
            <div class="destination-card">
                <h3><b>Chitwan</b></h3>
                <p>90 Hotels</p>
            </div>
            <div class="destination-card">
                <h3><b>Lumbini</b></h3>
                <p>75 Hotels</p>
            </div>
        </div>
    </section>

    <section class="section light">
        <div class="container">
            <h2>Featured Hotels</h2>

            <div class="hotel-grid">
            @foreach ($featuredHotels as $hotel)
                @if ($featuredHotels->isEmpty()) 
                    <p>There are no hotels available</p>
                @else
                    <div class="hotel-card">
                        <img src="https://picsum.photos/500/320?random={{ $hotel }}" alt="">
                        <div class="hotel-body">
                            <div class="rating">
                                <div class="stars"> <span class="stars-empty">★★★★★</span> <span class="stars-filled"
                                        style="width: {{ ($hotel->star_rating / 5) * 100 }}%;"> ★★★★★ </span> </div>
                                <span class="rating-number"> {{ number_format($hotel->star_rating, 1) }} </span>
                            </div>
                            <h3><b>{{ $hotel->name }}</b></h3>
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
        </div>
    </section>

    <section class="section container">
        <h2>Why Choose Us</h2>

        <div class="features">
            <div class="feature">
                <h3>Best Price</h3>
                <p>Competitive hotel pricing.</p>
            </div>
            <div class="feature">
                <h3>Instant Booking</h3>
                <p>Reserve your room instantly.</p>
            </div>
            <div class="feature">
                <h3>Secure Payments</h3>
                <p>Safe payment experience.</p>
            </div>
            <div class="feature">
                <h3>24/7 Support</h3>
                <p>Always here to help.</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready for your next adventure?</h2>
            <p>Browse hundreds of verified hotels and book in minutes.</p>
            <a href="/hotels" class="btn large">Explore Hotels</a>
        </div>
    </section>

    <footer>
        <div class="container footer-grid">
            <div>
                <h3>Esewa Hotels</h3>
                <p>Your trusted booking platform.</p>
            </div>
            <div>
                <h4>Company</h4>
                <a href="/about">About</a><br>
                <a href="/contact">Contact</a>
            </div>
            <div>
                <h4>Support</h4>
                <a href="#">FAQ</a><br>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
        <div class="copyright">
            © {{ date('Y') }} Esewa Hotels. All rights reserved.
        </div>
    </footer>
</x-layout>
