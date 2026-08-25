<x-owner-layout>

    <main class="page-container">

        {{-- =====================================================
             PAGE HEADER
             ===================================================== --}}

        <div class="page-header">

            <div>

                <h1>Edit Room</h1>

                <p>
                    Update the details of Room {{ $room->room_number }}.
                </p>

            </div>


            <div class="page-header-actions">
                <a href="{{ route('hotels.show', $hotel) }}" class="btn">
                    Back to Hotel
                </a>
            </div>
        </div>
        {{-- =====================================================
             VALIDATION ERRORS
             ===================================================== --}}

        @if ($errors->any())
            <div class="card form-errors">
                <h3>Please fix the following errors:</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- =====================================================
             EDIT ROOM
             ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Room Information</h2>

                    <p>
                        Update the details of this physical room.
                    </p>

                </div>

            </div>


            <div class="card">

                <form
                    action="{{ route('rooms.update', [
                        'hotel' => $hotel->id,
                        'room' => $room->id,
                    ]) }}"
                    method="POST"
                    class="form"
                >

                    @csrf

                    @method('PUT')


                    {{-- =================================================
                         ROOM TYPE
                         ================================================= --}}

                    <div class="form-group">

                        <label for="room_type_id">
                            Room Type
                        </label>

                        <select
                            id="room_type_id"
                            name="room_type_id"
                            class="input"
                            required
                        >

                            <option value="">
                                Select Room Type
                            </option>

                            @foreach ($hotel->roomTypes as $roomType)

                                <option
                                    value="{{ $roomType->id }}"
                                    {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}
                                >
                                    {{ $roomType->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('room_type_id')

                            <span class="error-message">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         ROOM NUMBER
                         ================================================= --}}

                    <div class="form-group">

                        <label for="room_number">
                            Room Number
                        </label>

                        <input
                            id="room_number"
                            type="text"
                            name="room_number"
                            class="input"
                            value="{{ old('room_number', $room->room_number) }}"
                            placeholder="e.g. 101"
                            required
                        >

                        @error('room_number')

                            <span class="error-message">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         ROOM NAME
                         ================================================= --}}

                    <div class="form-group">

                        <label for="name">
                            Room Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="input"
                            value="{{ old('name', $room->name) }}"
                            placeholder="e.g. Mountain View Room"
                        >

                        @error('name')

                            <span class="error-message">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                         ================================================= --}}

                    <div class="form-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="input"
                            required
                        >

                            <option
                                value="available"
                                {{ old('status', $room->status) === 'available' ? 'selected' : '' }}
                            >
                                Available
                            </option>

                            <option
                                value="maintenance"
                                {{ old('status', $room->status) === 'maintenance' ? 'selected' : '' }}
                            >
                                Maintenance
                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $room->status) === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')

                            <span class="error-message">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         ACTIONS
                         ================================================= --}}

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
                            Update Room
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</x-owner-layout>