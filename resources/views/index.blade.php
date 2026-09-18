
<x-layout>

    {{-- Hero / Search --}}
    <section class="hero">
        <div class="overlay"></div>

        <div class="hero-content">
            <h1>Find Your Perfect Stay</h1>

            <p>
                Book luxury hotels, resorts and budget rooms across Nepal.
            </p>

            <form
                action="{{ route('hotels.index') }}"
                method="GET"
                class="search-card"
            >

                {{-- Destination --}}
                <input
                    type="text"
                    name="location"
                    placeholder="Destination"
                    value="{{ request('location') }}"
                >

                {{-- Check In --}}
                <input
                    type="date"
                    name="check_in"
                    value="{{ request('check_in') }}"
                >

                {{-- Check Out --}}
                <input
                    type="date"
                    name="check_out"
                    value="{{ request('check_out') }}"
                >

                {{-- Guests --}}
                <select
                    class="select_options"
                    name="guests"
                >
                    <option value="1" {{ request('guests') == 1 ? 'selected' : '' }}>
                        1 Guest
                    </option>

                    <option value="2" {{ request('guests') == 2 ? 'selected' : '' }}>
                        2 Guests
                    </option>

                    <option value="3" {{ request('guests') == 3 ? 'selected' : '' }}>
                        3 Guests
                    </option>

                    <option value="4" {{ request('guests') == 4 ? 'selected' : '' }}>
                        4+ Guests
                    </option>
                </select>

                <button type="submit">
                    Search Hotels
                </button>

            </form>
        </div>
    </section>


    {{-- Popular Destinations --}}
    <section class="section container">

        <h2>Popular Destinations</h2>

        <div class="destinations">

            <div class="destination-card">
                <h3>
                    <b>Kathmandu</b>
                </h3>

                <p>240 Hotels</p>
            </div>

            <div class="destination-card">
                <h3>
                    <b>Pokhara</b>
                </h3>

                <p>180 Hotels</p>
            </div>

            <div class="destination-card">
                <h3>
                    <b>Chitwan</b>
                </h3>

                <p>90 Hotels</p>
            </div>

            <div class="destination-card">
                <h3>
                    <b>Lumbini</b>
                </h3>

                <p>75 Hotels</p>
            </div>

        </div>

    </section>


    {{-- Featured Hotels --}}
    <section class="section light">

        <div class="container">

            <h2>Featured Hotels</h2>

            @if ($featuredHotels->isEmpty())

                <p>There are no featured hotels available.</p>

            @else

                <div class="hotel-grid">

                    @foreach ($featuredHotels as $hotel)

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
                                    <b>
                                        {{ $hotel->name }}
                                    </b>
                                </h3>


                                {{-- Location --}}
                                <p class="hotel-location">

                                    {{ $hotel->city?->name ?? 'Location unavailable' }}

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


                                {{-- View Hotel --}}
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

                    @endforeach

                </div>

            @endif

        </div>

    </section>


    {{-- Why Choose Us --}}
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


    {{-- CTA --}}
    <section class="cta">

        <div class="container">

            <h2>
                Ready for your next adventure?
            </h2>

            <p>
                Browse hundreds of verified hotels and book in minutes.
            </p>

            <a
                href="{{ route('hotels.index') }}"
                class="btn large"
            >
                Explore Hotels
            </a>

        </div>

    </section>


    {{-- Footer --}}
    <footer>

        <div class="container footer-grid">

            <div>
                <h3>Esewa Hotels</h3>

                <p>
                    Your trusted booking platform.
                </p>
            </div>


            <div>

                <h4>Company</h4>

                <a href="/about">
                    About
                </a>

                <br>

                <a href="/contact">
                    Contact
                </a>

            </div>


            <div>

                <h4>Support</h4>

                <a href="#">
                    FAQ
                </a>

                <br>

                <a href="#">
                    Privacy Policy
                </a>

            </div>

        </div>


        <div class="copyright">

            © {{ date('Y') }} Esewa Hotels. All rights reserved.

        </div>

    </footer>

</x-layout>
