<x-owner-layout>

    <main class="hotel-list-page">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="hotel-list-header">

            <div>
                <h1>My Hotels</h1>

                <p>
                    Manage your hotels and their current status.
                </p>
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIVE HOTELS --}}
        {{-- ========================================================= --}}

        <section class="hotel-section">

            <div class="section-header active-section-header">

                <h2>Active Hotels</h2>

                <p>
                    Hotels currently available to customers.
                </p>

                <span class="section-count">
                    {{ $activeHotels->count() }}
                </span>

            </div>


            @if ($activeHotels->isNotEmpty())

                <div class="hotel-grid">

                    @foreach ($activeHotels as $hotel)

                        <x-owner-hotel-card
                            :hotel="$hotel"
                        />

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <p>
                        You don't have any active hotels.
                    </p>

                </div>

            @endif

        </section>


        {{-- ========================================================= --}}
        {{-- PENDING HOTELS --}}
        {{-- ========================================================= --}}

        <section class="hotel-section">

            <div class="section-header pending-section-header">

                <h2>Pending Hotels</h2>

                <p>
                    Hotels waiting for approval.
                </p>

                <span class="section-count">
                    {{ $pendingHotels->count() }}
                </span>

            </div>


            @if ($pendingHotels->isNotEmpty())

                <div class="hotel-grid">

                    @foreach ($pendingHotels as $hotel)

                        <x-owner-hotel-card
                            :hotel="$hotel"
                        />

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <p>
                        You don't have any pending hotels.
                    </p>

                </div>

            @endif

        </section>


        {{-- ========================================================= --}}
        {{-- INACTIVE HOTELS --}}
        {{-- ========================================================= --}}

        <section class="hotel-section">

            <div class="section-header inactive-section-header">

                <h2>Inactive Hotels</h2>

                <p>
                    Hotels that are currently unavailable to customers.
                </p>

                <span class="section-count">
                    {{ $inactiveHotels->count() }}
                </span>

            </div>


            @if ($inactiveHotels->isNotEmpty())

                <div class="hotel-grid">

                    @foreach ($inactiveHotels as $hotel)

                        <x-owner-hotel-card
                            :hotel="$hotel"
                        />

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <p>
                        You don't have any inactive hotels.
                    </p>

                </div>

            @endif

        </section>

    </main>

</x-owner-layout>