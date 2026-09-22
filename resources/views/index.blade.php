<x-layout>
    <section class="hero">
        <div class="overlay"></div>
        <div class="hero-content container">
            <div class="hero-copy">
                <span class="eyebrow eyebrow-light">Your stay starts here</span>
                <h1>Find your perfect<br><span>stay in Nepal.</span></h1>
                <p>Discover comfortable hotels, memorable locations and effortless booking, all in one place.</p>
            </div>

            <form action="{{ route('hotels.index') }}" method="GET" class="search-card">
                <div class="search-field search-field-location">
                    <label for="location">Destination</label>
                    <div class="search-input-wrap"><span class="search-icon" aria-hidden="true">⌖</span><input id="location" type="text" name="location" placeholder="Where are you going?" value="{{ request('location') }}"></div>
                </div>
                <div class="search-field"><label for="check-in">Check in</label><input id="check-in" type="date" name="check_in" value="{{ request('check_in') }}"></div>
                <div class="search-field"><label for="check-out">Check out</label><input id="check-out" type="date" name="check_out" value="{{ request('check_out') }}"></div>
                <div class="search-field"><label for="guests">Guests</label><select id="guests" name="guests">
                    @foreach ([1, 2, 3, 4] as $guestCount)
                        <option value="{{ $guestCount }}" {{ request('guests', 1) == $guestCount ? 'selected' : '' }}>{{ $guestCount }}{{ $guestCount === 4 ? '+' : '' }} Guest{{ $guestCount === 1 ? '' : 's' }}</option>
                    @endforeach
                </select></div>
                <button type="submit" class="search-submit">Search <span aria-hidden="true">→</span></button>
            </form>
        </div>
        <div class="hero-scroll" aria-hidden="true"><span></span> Explore stays</div>
    </section>

    <section class="quick-stats">
        <div class="container quick-stats-grid">
            <div><strong>500+</strong><span>verified hotels</span></div><div><strong>4.8/5</strong><span>guest rating</span></div><div><strong>24/7</strong><span>local support</span></div><div><strong>Secure</strong><span>easy payments</span></div>
        </div>
    </section>

    <section class="section container">
        <div class="section-heading"><div><span class="section-kicker">Explore Nepal</span><h2>Popular destinations</h2><p>Start with somewhere people love to stay.</p></div><a href="{{ route('hotels.index') }}" class="section-link">View all hotels <span>→</span></a></div>
        <div class="destinations">
            <a class="destination-card destination-kathmandu" href="{{ route('hotels.index', ['location' => 'Kathmandu']) }}"><span class="destination-shade"></span><div><h3>Kathmandu</h3><p>Heritage, culture and city life</p></div><span class="destination-arrow">↗</span></a>
            <a class="destination-card destination-pokhara" href="{{ route('hotels.index', ['location' => 'Pokhara']) }}"><span class="destination-shade"></span><div><h3>Pokhara</h3><p>Lakeside calm beneath the peaks</p></div><span class="destination-arrow">↗</span></a>
            <a class="destination-card destination-chitwan" href="{{ route('hotels.index', ['location' => 'Chitwan']) }}"><span class="destination-shade"></span><div><h3>Chitwan</h3><p>Wild trails and slower mornings</p></div><span class="destination-arrow">↗</span></a>
            <a class="destination-card destination-lumbini" href="{{ route('hotels.index', ['location' => 'Lumbini']) }}"><span class="destination-shade"></span><div><h3>Lumbini</h3><p>Peace, space and reflection</p></div><span class="destination-arrow">↗</span></a>
        </div>
    </section>

    <section class="section light"><div class="container">
        <div class="section-heading"><div><span class="section-kicker">Handpicked for you</span><h2>Featured hotels</h2><p>Comfortable places, ready when you are.</p></div><a href="{{ route('hotels.index') }}" class="section-link">Browse all stays <span>→</span></a></div>
        @if ($featuredHotels->isEmpty())
            <div class="empty-state"><p>There are no featured hotels available right now.</p><a class="btn" href="{{ route('hotels.index') }}">Explore hotels</a></div>
        @else
            <div class="hotel-grid">
                @foreach ($featuredHotels as $hotel)
                    @php $room = $hotel->roomTypes->sortBy(fn ($room) => $room->discount_price ?? $room->price)->first(); @endphp
                    <article class="hotel-card">
                        <a class="hotel-image" href="{{ route('hotels.show', $hotel->id) }}"><img src="{{ asset('storage/' . $hotel->featured_image) }}" alt="{{ $hotel->name }}"><span class="hotel-badge">Featured</span><span class="hotel-favorite" aria-label="Save hotel">♡</span></a>
                        <div class="hotel-body"><div class="hotel-meta"><span>{{ $hotel->city?->name ?? 'Nepal' }}</span><span class="rating">★ {{ number_format($hotel->star_rating, 1) }}</span></div><h3><a href="{{ route('hotels.show', $hotel->id) }}">{{ $hotel->name }}</a></h3><p class="amenities">{{ $hotel->amenity->pluck('name')->take(3)->implode(' · ') ?: 'Comfortable stay with thoughtful amenities' }}</p><div class="price-row">@if ($room)<strong>रु {{ number_format($room->discount_price ?? $room->price, 0) }} <small>/ night</small></strong>@else<strong>Price unavailable</strong>@endif<a href="{{ route('hotels.show', $hotel->id) }}">View stay <span>→</span></a></div></div>
                    </article>
                @endforeach
            </div>
        @endif
    </div></section>

    <section class="section container feature-section"><div class="feature-intro"><span class="section-kicker">Why choose us</span><h2>Everything you need for a better stay.</h2><p>From finding the right room to making a secure booking, we keep the whole experience simple.</p></div><div class="features"><div class="feature"><span class="feature-number">01</span><h3>Verified stays</h3><p>Book with confidence from a growing collection of trusted properties.</p></div><div class="feature"><span class="feature-number">02</span><h3>Clear pricing</h3><p>Know what you are paying before you confirm your room.</p></div><div class="feature"><span class="feature-number">03</span><h3>Easy booking</h3><p>Search, compare and reserve your next stay in a few simple steps.</p></div><div class="feature"><span class="feature-number">04</span><h3>Local support</h3><p>Our team is here to help whenever your travel plans need us.</p></div></div></section>

    <section class="cta"><div class="container cta-inner"><div><span class="section-kicker section-kicker-light">Your next stay is waiting</span><h2>Make room for<br>something good.</h2></div><a href="{{ route('hotels.index') }}" class="btn btn-light">Explore hotels <span>→</span></a></div></section>

    <footer><div class="container footer-grid"><div class="footer-brand"><img class="footer-logo" src="https://esewahotels.com/images/esewa_hotel_logo_white.svg" alt="eSewa Hotels"><p>Comfortable places for every kind of journey.</p></div><div><h4>Explore</h4><a href="{{ route('hotels.index') }}">Find a hotel</a><a href="/offers">Offers</a></div><div><h4>Account</h4><a href="{{ route('bookings.history') }}">My bookings</a><a href="/login">Log in</a></div><div><h4>Help</h4><a href="/contact">Contact us</a><a href="#">Privacy policy</a></div></div><div class="container copyright">© {{ date('Y') }} Esewa Hotels. All rights reserved.</div></footer>
+</x-layout>
