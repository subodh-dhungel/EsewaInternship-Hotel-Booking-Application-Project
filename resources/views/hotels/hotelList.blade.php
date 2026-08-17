<x-layout>

    <main>

        {{-- ===================================================== --}}
        {{-- CUSTOMER                                               --}}
        {{-- ===================================================== --}}

        @if (auth()->user()->hasRole('customer'))

            <h1>Hotels</h1>

            @if ($hotels->isEmpty())

                <p>
                    There are no hotels available.
                </p>

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


        {{-- ===================================================== --}}
        {{-- HOTEL OWNER                                            --}}
        {{-- ===================================================== --}}

        @elseif (auth()->user()->hasRole('hotel_owner'))


            {{-- ================================================= --}}
            {{-- ACTIVE HOTELS                                      --}}
            {{-- ================================================= --}}

            <h1>My Active Hotels</h1>

            @if ($activeHotels->isEmpty())

                <p>
                    You don't have any active hotels.
                </p>

            @else

                <div class="hotel-grid">

                    @foreach ($activeHotels as $hotel)

                        <x-hotel-card
                            :hotel="$hotel"
                            status="active"
                        />

                    @endforeach

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- PENDING HOTELS                                     --}}
            {{-- ================================================= --}}

            <h1>My Pending Hotels</h1>

            @if ($pendingHotels->isEmpty())

                <p>
                    You don't have any pending hotels.
                </p>

            @else

                <div class="hotel-grid">

                    @foreach ($pendingHotels as $hotel)

                        <x-hotel-card
                            :hotel="$hotel"
                            status="pending"
                        />

                    @endforeach

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- INACTIVE HOTELS                                    --}}
            {{-- ================================================= --}}

            <h1>My Inactive Hotels</h1>

            @if ($inactiveHotels->isEmpty())

                <p>
                    You don't have any inactive hotels.
                </p>

            @else

                <div class="hotel-grid">

                    @foreach ($inactiveHotels as $hotel)

                        <x-hotel-card
                            :hotel="$hotel"
                            status="inactive"
                        />

                    @endforeach

                </div>

            @endif

        @endif

    </main>

</x-layout>