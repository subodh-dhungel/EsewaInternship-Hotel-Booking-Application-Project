<x-layout>

    <main class="hotel-list-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="page-header hotel-list-header">

            <div class="hotel-list-header-content">

                <h1>Hotels</h1>

                <p>
                    Find the perfect hotel for your stay.
                </p>

            </div>

        </div>


        {{-- =====================================================
             AVAILABLE HOTELS
        ====================================================== --}}

        <section class="hotel-section card">

            {{-- Section Header --}}

            <div class="section-header active-section-header">

                <div>

                    <h2>Available Hotels</h2>

                    <p>
                        Browse hotels currently available for booking.
                    </p>

                </div>

                <span class="section-count">
                    {{ $hotels->count() }}
                </span>

            </div>


            {{-- =================================================
                 HOTEL LIST
            ================================================== --}}

            @if ($hotels->isEmpty())

                <div class="empty-state">

                    <h3>
                        No hotels available
                    </h3>

                    <p>
                        There are currently no hotels available for booking.
                        Please check again later.
                    </p>

                </div>

            @else

                <div class="hotel-grid">

                    @foreach ($hotels as $hotel)

                        <x-hotel-card
                            :hotel="$hotel"
                            status="customer"
                        />

                    @endforeach

                </div>

            @endif

        </section>

    </main>

</x-layout>