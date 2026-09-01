<x-layout>
    <main class="page-container">
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
                <a href="{{ route('hotels.show', $hotel) }}" class="btn">
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
                            Rs. {{ number_format($room_type->price, 2) }} / night
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
                    action="{{ route('bookings.store', [
                        'hotel' => $hotel->id,
                        'room_type' => $room_type->id,
                    ]) }}"
                    method="POST"
                >

                    @csrf


                    {{-- =====================================================
                     CHECK-IN AND CHECK-OUT
                     ===================================================== --}}

                    <div class="form-grid">

                        {{-- Check-in --}}

                        <div class="form-group">

                            <label for="check-in">
                                Check-in
                            </label>

                            <input
                                id="check-in"
                                class="input"
                                type="date"
                                name="check-in"
                                value="{{ old('check-in') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                            >

                            @error('check-in')
                                <p class="form-error">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Check-out --}}

                        <div class="form-group">

                            <label for="check-out">
                                Check-out
                            </label>

                            <input
                                id="check-out"
                                class="input"
                                type="date"
                                name="check-out"
                                value="{{ old('check-out') }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}"
                                required
                            >

                            @error('check-out')
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
                                value="{{ old('adults', 1) }}"
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
                                value="{{ old('children', 0) }}"
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
                            value="{{ old('number_of_rooms', 1) }}"
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

                        <label for="number_of_rooms">
                            Phone number
                        </label>

                        <input
                            id="number_of_rooms"
                            class="input"
                            type="text"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
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

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Continue Booking
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</x-layout>