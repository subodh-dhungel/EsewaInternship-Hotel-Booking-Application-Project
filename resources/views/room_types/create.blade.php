<x-owner-layout>

    <main class="page-container">

        {{-- =====================================================
             PAGE HEADER
             ===================================================== --}}

        <div class="page-header">

            <div>

                <h1>Add Room Type</h1>

                <p>
                    Add a new room type to {{ $hotel->name }}.
                </p>

            </div>

            <div class="page-header-actions">

                <a
                    href="{{ route('owner.hotels.show', $hotel) }}"
                    class="btn"
                >
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
             ROOM TYPE FORM
             ===================================================== --}}

        <form
            action="{{ route('room-types.store', $hotel) }}"
            method="POST"
            class="hotel-form"
        >

            @csrf


            {{-- =================================================
                 BASIC INFORMATION
                 ================================================= --}}

            <div class="form-section">

                <div class="form-section-title">

                    <h2>Room Information</h2>

                    <p>
                        Provide the basic information about this room type.
                    </p>

                </div>


                <div class="form-grid">

                    {{-- Room Type Name --}}

                    <div class="form-group">

                        <label for="name">
                            Room Type Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Standard Room"
                            required
                        >

                    </div>


                    {{-- Capacity --}}

                    <div class="form-group">

                        <label for="capacity">
                            Guest Capacity
                        </label>

                        <input
                            type="number"
                            id="capacity"
                            name="capacity"
                            value="{{ old('capacity') }}"
                            min="1"
                            placeholder="e.g. 2"
                            required
                        >

                    </div>


                    {{-- Description --}}

                    <div class="form-group full-width">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            placeholder="Describe this room type..."
                            required
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PRICING
                 ================================================= --}}

            <div class="form-section">

                <div class="form-section-title">

                    <h2>Pricing</h2>

                    <p>
                        Set the nightly price for this room type.
                    </p>

                </div>


                <div class="form-grid">

                    <div class="form-group">

                        <label for="price">
                            Price Per Night
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            placeholder="e.g. 3500"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="discount_price">
                            Discount Price
                        </label>

                        <input
                            type="number"
                            id="discount_price"
                            name="discount_price"
                            value="{{ old('discount_price') }}"
                            min="0"
                            step="0.01"
                            placeholder="Optional"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ROOM DETAILS
                 ================================================= --}}

            <div class="form-section">

                <div class="form-section-title">

                    <h2>Room Details</h2>

                    <p>
                        Specify the physical characteristics of this room type.
                    </p>

                </div>


                <div class="form-grid">

                    {{-- Bed Type --}}

                    <div class="form-group">

                        <label for="bed_type">
                            Bed Type
                        </label>

                        <select
                            id="bed_type"
                            name="bed_type"
                            required
                        >

                            <option value="">
                                Select bed type
                            </option>

                            <option
                                value="single"
                                {{ old('bed_type') == 'single' ? 'selected' : '' }}
                            >
                                Single Bed
                            </option>

                            <option
                                value="double"
                                {{ old('bed_type') == 'double' ? 'selected' : '' }}
                            >
                                Double Bed
                            </option>

                            <option
                                value="queen"
                                {{ old('bed_type') == 'queen' ? 'selected' : '' }}
                            >
                                Queen Bed
                            </option>

                            <option
                                value="king"
                                {{ old('bed_type') == 'king' ? 'selected' : '' }}
                            >
                                King Bed
                            </option>

                            <option
                                value="twin"
                                {{ old('bed_type') == 'twin' ? 'selected' : '' }}
                            >
                                Twin Beds
                            </option>

                        </select>

                    </div>


                    {{-- Room Size --}}

                    <div class="form-group">

                        <label for="room_size">
                            Room Size (sq. ft.)
                        </label>

                        <input
                            type="number"
                            id="room_size"
                            name="room_size"
                            value="{{ old('room_size') }}"
                            min="1"
                            placeholder="e.g. 350"
                            required
                        >

                    </div>

                </div>

            </div>

            {{-- =================================================
                 ACTIONS
                 ================================================= --}}

            <div class="form-actions">

                <a
                    href="{{ route('owner.hotels.show', $hotel) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Room Type
                </button>

            </div>

        </form>

    </main>

</x-owner-layout>