<x-layout>
    <main class="page-container">

```
    {{-- =====================================================
     PAGE HEADER
     ===================================================== --}}
    <div class="page-header">

        <div>
            <h1>Book Your Stay</h1>

            <p>
                Complete the details below to book your room.
            </p>
        </div>

        <div class="page-header-actions">

            <a
                href="{{ route('hotels.show', $hotel) }}"
                class="btn"
            >
                Back to Hotel
            </a>

        </div>

    </div>


    {{-- =====================================================
     HOTEL AND ROOM TYPE INFORMATION
     ===================================================== --}}
    <section class="details-section">

        <div class="section-header">

            <div>
                <h2>Booking Details</h2>

                <p>
                    You are booking a room at this hotel.
                </p>
            </div>

        </div>


        <div class="card booking-hotel-card">

            <div class="details-grid">

                {{-- Hotel Name --}}
                <div class="detail-item">

                    <span class="detail-label">
                        Hotel
                    </span>

                    <span class="detail-value">
                        {{ $hotel->name }}
                    </span>

                </div>


                {{-- Room Type --}}
                <div class="detail-item">

                    <span class="detail-label">
                        Room Type
                    </span>

                    <span class="detail-value">
                        {{ $room_type->name }}
                    </span>

                </div>


                {{-- Price --}}
                <div class="detail-item">

                    <span class="detail-label">
                        Price
                    </span>

                    <span class="detail-value">
                        Rs.
                        {{ number_format($room_type->discount_price ?? $room_type->price, 2) }}
                        / night
                    </span>

                </div>


                {{-- Capacity --}}
                <div class="detail-item">

                    <span class="detail-label">
                        Capacity
                    </span>

                    <span class="detail-value">
                        {{ $room_type->capacity }} Guests
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
     AVAILABILITY RESULT
     ===================================================== --}}
    @if(isset($available))

        <section class="details-section">

            <div class="card">

                @if($available)

                    <h2>Rooms Available</h2>

                    <p>
                        Your requested number of rooms is available.
                    </p>

                    <p>
                        Available rooms:
                        <strong>{{ $available_rooms }}</strong>
                    </p>

                @else

                    <h2>Rooms Not Available</h2>

                    <p>
                        The requested number of rooms are not available
                        for the selected dates.
                    </p>

                    <p>
                        Available rooms:
                        <strong>{{ $available_rooms }}</strong>
                    </p>

                @endif

            </div>

        </section>

    @endif


    {{-- =====================================================
     BOOKING FORM
     ===================================================== --}}
    <section class="details-section">

        <div class="section-header">

            <div>

                <h2>Stay Information</h2>

                <p>
                    Select your dates and the number of guests.
                </p>

            </div>

        </div>


        <div class="card booking-form-card">

            <form
                action="{{ route('bookings.checkAvailability', [$hotel, $room_type]) }}"
                method="POST"
            >

                @csrf


                {{-- =====================================================
                 CHECK-IN / CHECK-OUT
                 ===================================================== --}}
                <div class="form-grid">

                    {{-- Check-in --}}
                    <div class="form-group">

                        <label for="check_in">
                            Check-in
                        </label>

                        <input
                            id="check_in"
                            class="input"
                            type="date"
                            name="check_in"
                            value="{{ old('check_in', $bookingData['check_in'] ?? '') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            required
                        >

                        @error('check_in')
                            <p class="form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Check-out --}}
                    <div class="form-group">

                        <label for="check_out">
                            Check-out
                        </label>

                        <input
                            id="check_out"
                            class="input"
                            type="date"
                            name="check_out"
                            value="{{ old('check_out', $bookingData['check_out'] ?? '') }}"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            required
                        >

                        @error('check_out')
                            <p class="form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
                 GUEST INFORMATION
                 ===================================================== --}}
                <div class="form-grid">

                    {{-- Adults --}}
                    <div class="form-group">

                        <label for="adults">
                            Adults
                        </label>

                        <input
                            id="adults"
                            class="input"
                            type="number"
                            name="adults"
                            value="{{ old('adults', $bookingData['adults'] ?? 1) }}"
                            min="1"
                            required
                        >

                        @error('adults')
                            <p class="form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Children --}}
                    <div class="form-group">

                        <label for="children">
                            Children
                        </label>

                        <input
                            id="children"
                            class="input"
                            type="number"
                            name="children"
                            value="{{ old('children', $bookingData['children'] ?? 0) }}"
                            min="0"
                            required
                        >

                        @error('children')
                            <p class="form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
                 NUMBER OF ROOMS
                 ===================================================== --}}
                <div class="form-group">

                    <label for="number_of_rooms">
                        Number of Rooms
                    </label>

                    <input
                        id="number_of_rooms"
                        class="input"
                        type="number"
                        name="number_of_rooms"
                        value="{{ old('number_of_rooms', $bookingData['number_of_rooms'] ?? 1) }}"
                        min="1"
                        required
                    >

                    @error('number_of_rooms')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =====================================================
                 PHONE NUMBER
                 ===================================================== --}}
                <div class="form-group">

                    <label for="phone_number">
                        Phone Number
                    </label>

                    <input
                        id="phone_number"
                        class="input"
                        type="text"
                        name="phone_number"
                        value="{{ old('phone_number', $bookingData['phone_number'] ?? '') }}"
                        required
                    >

                    @error('phone_number')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =====================================================
                 BOOKING ACTIONS
                 ===================================================== --}}
                <div class="form-actions">

                    <a
                        href="{{ route('hotels.show', $hotel) }}"
                        class="btn"
                    >
                        Cancel
                    </a>


                    @if(isset($available) && $available)

                        <button
                            type="submit"
                            formaction="{{ route('bookings.store', [$hotel, $room_type]) }}"
                            class="btn btn-primary"
                        >
                            Continue to Booking
                        </button>

                    @else

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Check Availability
                        </button>

                    @endif

                </div>

            </form>

        </div>

    </section>

</main>
```

</x-layout>
