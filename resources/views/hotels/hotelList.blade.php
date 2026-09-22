<x-layout>
    <main class="hotel-list-page">
        <header class="hotel-list-header">
            <div class="container hotel-list-header__inner">
                <div>
                    <span class="section-kicker">Find your next stay</span>
                    <h1>Hotels in Nepal</h1>
                    <p>Compare trusted stays, prices and locations in one place.</p>
                </div>
                <div class="hotel-list-header__mark" aria-hidden="true">⌖</div>
            </div>
        </header>

        <section class="container hotel-search-panel" aria-label="Search and filter hotels">
            <form action="{{ route('hotels.index') }}" method="GET" class="hotel-filter-form">
                <div class="filter-search-row">
                    <div class="filter-search-field">
                        <label for="hotel-search">Search</label>
                        <span aria-hidden="true">⌕</span>
                        <input id="hotel-search" type="search" name="search" value="{{ $search }}" placeholder="Hotel name or city">
                    </div>
                    <button class="filter-submit" type="submit"><span>Apply filters</span><b aria-hidden="true">→</b></button>
                </div>
                <div class="filter-options">
                    <div class="filter-field">
                        <label for="city-id">Destination</label>
                        <select id="city-id" name="city_id">
                            <option value="">All destinations</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ (string) $cityId === (string) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label for="min-rating">Guest rating</label>
                        <select id="min-rating" name="min_rating">
                            <option value="">Any rating</option>
                            @foreach ([4, 3, 2] as $rating)
                                <option value="{{ $rating }}" {{ (string) $minRating === (string) $rating ? 'selected' : '' }}>{{ $rating }}+ stars</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label for="max-price">Nightly budget</label>
                        <select id="max-price" name="max_price">
                            <option value="">Any price</option>
                            <option value="3000" {{ (string) $maxPrice === '3000' ? 'selected' : '' }}>Up to रु 3,000</option>
                            <option value="6000" {{ (string) $maxPrice === '6000' ? 'selected' : '' }}>Up to रु 6,000</option>
                            <option value="10000" {{ (string) $maxPrice === '10000' ? 'selected' : '' }}>Up to रु 10,000</option>
                            @if ($priceMaximum > 10000)<option value="{{ $priceMaximum }}" {{ (string) $maxPrice === (string) $priceMaximum ? 'selected' : '' }}>Up to रु {{ number_format($priceMaximum) }}</option>@endif
                        </select>
                    </div>
                    <div class="filter-field">
                        <label for="sort">Sort by</label>
                        <select id="sort" name="sort">
                            <option value="recommended" {{ $sort === 'recommended' ? 'selected' : '' }}>Recommended</option>
                            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Highest rated</option>
                            <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: low to high</option>
                            <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: high to low</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>
                </div>
                @if ($search || $cityId || $minRating || $maxPrice || $sort !== 'recommended')
                    <div class="active-filters"><span>Filters active</span><a href="{{ route('hotels.index') }}">Clear all</a></div>
                @endif
            </form>
        </section>

        <section class="container hotel-results-section">
            <div class="results-heading">
                <div><h2>{{ $hotels->count() }} {{ $hotels->count() === 1 ? 'stay' : 'stays' }} found</h2><p>{{ $search ? 'Showing results for “' . $search . '”' : 'Available properties ready for booking' }}</p></div>
                <span class="results-view-label">Updated today</span>
            </div>

            @if ($hotels->isEmpty())
                <div class="hotel-empty-state"><div class="hotel-empty-state__icon">⌕</div><h3>No stays match those filters</h3><p>Try widening your search or clearing one of the filters.</p><a class="btn" href="{{ route('hotels.index') }}">Clear filters</a></div>
            @else
                <div class="hotel-grid hotel-grid--results">
                    @foreach ($hotels as $hotel)
                        <x-hotel-card :hotel="$hotel" status="customer" />
                    @endforeach
                </div>
            @endif
        </section>
    </main>
</x-layout>
