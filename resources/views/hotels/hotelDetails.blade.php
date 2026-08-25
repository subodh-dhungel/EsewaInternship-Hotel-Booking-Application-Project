<x-owner-layout>

    <main class="page-container">

        {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}

        <div class="page-header">

            <div>

                <h1>{{ $hotel->name }}</h1>

                <p>
                    Hotel information and image management.
                </p>

            </div>


            <div class="page-header-actions">

                <a href="{{ route('owner.hotels.index') }}" class="btn">
                    Back to Hotels
                </a>

                <a href="{{ route('rooms.index', $hotel) }}" class="btn">
                    Manage Rooms
                </a>

            </div>

        </div>


        {{-- =====================================================
         HOTEL INFORMATION
         ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Hotel Information</h2>

                    <p>
                        Basic information about this hotel.
                    </p>

                </div>

            </div>


            <div class="card hotel-details-card">

                <div class="details-grid">

                    <div class="detail-item">

                        <span class="detail-label">
                            Hotel Name
                        </span>

                        <span class="detail-value">
                            {{ $hotel->name }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Rating
                        </span>

                        <div class="rating">

                            <div class="stars">

                                <span class="stars-empty">
                                    ★★★★★
                                </span>

                                <span class="stars-filled" style="width: {{ ($hotel->star_rating / 5) * 100 }}%;">
                                    ★★★★★
                                </span>

                            </div>

                            <span class="rating-number">
                                {{ number_format($hotel->star_rating, 1) }}
                            </span>

                        </div>

                    </div>


                    <div class="detail-item detail-full">

                        <span class="detail-label">
                            Description
                        </span>

                        <p class="detail-value">
                            {{ $hotel->description }}
                        </p>

                    </div>


                    <div class="detail-item detail-full">

                        <span class="detail-label">
                            Address
                        </span>

                        <span class="detail-value">

                            {{ $hotel->address }},
                            {{ $hotel->city }},
                            {{ $hotel->district }},
                            {{ $hotel->country }}

                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            GPS Coordinates
                        </span>

                        <span class="detail-value">

                            @if ($hotel->latitude && $hotel->longitude)
                                {{ $hotel->latitude }}° N,
                                {{ $hotel->longitude }}° E
                            @else
                                Not provided
                            @endif

                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Phone
                        </span>

                        <span class="detail-value">
                            {{ $hotel->phone }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Email
                        </span>

                        <span class="detail-value">
                            {{ $hotel->email }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Check-in
                        </span>

                        <span class="detail-value">
                            {{ $hotel->checkin_time }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Check-out
                        </span>

                        <span class="detail-value">
                            {{ $hotel->check_out_time }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Listed On
                        </span>

                        <span class="detail-value">
                            {{ $hotel->created_at }}
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
         HOTEL GALLERY
         ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Hotel Gallery</h2>

                    <p>
                        Upload and manage images for this hotel.
                    </p>

                </div>

            </div>


            {{-- IMAGE UPLOAD --}}

            <div class="card image-upload-card">

                <form action="{{ route('owner.hotel-images.store', $hotel) }}" method="POST"
                    enctype="multipart/form-data" class="image-upload-form">

                    @csrf

                    <div>

                        <label for="hotel-image">
                            Add Hotel Image
                        </label>

                        <input id="hotel-image" class="input" type="file" name="image" accept="image/*" required>

                    </div>

                    <button class="btn" type="submit">
                        Upload Image
                    </button>

                </form>

            </div>


            {{-- GALLERY --}}

            @if ($hotel->image->isEmpty())

                <div class="empty-state card">

                    <h3>No images yet</h3>

                    <p>
                        Upload your first hotel image to build the gallery.
                    </p>

                </div>
            @else
                <div class="hotel-gallery">

                    @foreach ($hotel->image as $image)
                        <div class="gallery-item">

                            <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $hotel->name }}"
                                loading="lazy">


                            <div class="gallery-item-footer">

                                <form action="{{ route('owner.hotel-image.destroy', $image) }}" method="POST"
                                    class="delete-image-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-alert">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>

            @endif

        </section>


        {{-- =====================================================
         ROOM TYPES
         ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Room Types</h2>

                    <p>
                        Manage the different types of rooms available in this hotel.
                    </p>

                </div>

                <div class="section-header-actions">

                    <a href="{{ route('room-types.create', $hotel) }}" class="btn btn-primary">
                        + Add Room Type
                    </a>

                </div>

            </div>


            @if ($hotel->roomTypes->isEmpty())

                <div class="empty-state card">

                    <h3>No room types yet</h3>

                    <p>
                        Add your first room type to start managing rooms in this hotel.
                    </p>

                    <a href="{{ route('room-types.create', $hotel) }}" class="btn btn-primary">
                        Add Room Type
                    </a>

                </div>
            @else
                <div class="room-types-grid">

                    @foreach ($hotel->roomTypes as $roomType)
                        <div class="card room-type-card">

                            {{-- Room Type Header --}}

                            <div class="room-type-header">

                                <div>

                                    <h3>
                                        {{ $roomType->name }}
                                    </h3>

                                    <p class="room-type-description">
                                        {{ $roomType->description }}
                                    </p>

                                </div>

                            </div>


                            {{-- Price --}}

                            <div class="room-type-price">

                                <span class="room-type-price-value">
                                    Rs. {{ number_format($roomType->price, 2) }}
                                </span>

                                <span class="room-type-price-label">
                                    / night
                                </span>

                            </div>


                            {{-- Room Information --}}

                            <div class="room-type-details">

                                <div class="room-type-detail">

                                    <span class="detail-label">
                                        Capacity
                                    </span>

                                    <span class="detail-value">
                                        {{ $roomType->capacity }} Guests
                                    </span>

                                </div>


                                <div class="room-type-detail">

                                    <span class="detail-label">
                                        Bed Type
                                    </span>

                                    <span class="detail-value">
                                        {{ ucfirst($roomType->bed_type) }}
                                    </span>

                                </div>


                                <div class="room-type-detail">

                                    <span class="detail-label">
                                        Room Size
                                    </span>

                                    <span class="detail-value">
                                        {{ $roomType->room_size }} sq. ft.
                                    </span>

                                </div>


                                <div class="room-type-detail">

                                    <span class="detail-label">
                                        Total Rooms
                                    </span>

                                    <span class="detail-value">
                                        {{ $roomType->total_rooms }}
                                    </span>

                                </div>


                                <div class="room-type-detail">

                                    <span class="detail-label">
                                        Available
                                    </span>

                                    <span class="detail-value">
                                        {{ $roomType->available_rooms }}
                                    </span>

                                </div>

                            </div>


                            {{-- Actions --}}

                            <div class="room-type-actions">

                                <a href="{{ route('room-types.edit', [
                                    'hotel' => $hotel->id,
                                    'room_type' => $roomType->id,
                                ]) }}"
                                    class="btn">
                                    Edit
                                </a>


                                <form
                                    action="{{ route('room-types.destroy', [
                                        'hotel' => $hotel->id,
                                        'room_type' => $roomType->id,
                                    ]) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-alert">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>

            @endif

        </section>


        {{-- =====================================================
         ROOMS
         ===================================================== --}}

        <section class="details-section">

            <div class="section-header">

                <div>

                    <h2>Rooms</h2>

                    <p>
                        Manage the physical rooms available in this hotel.
                    </p>

                </div>

                <div class="section-header-actions">

                    <a href="{{ route('rooms.create', $hotel) }}" class="btn btn-primary">
                        + Add Room
                    </a>

                </div>

            </div>


            @if ($hotel->rooms->isEmpty())

                <div class="empty-state card">

                    <h3>No rooms yet</h3>

                    <p>
                        Add your first physical room to start managing rooms in this hotel.
                    </p>

                    <a href="{{ route('rooms.create', $hotel) }}" class="btn btn-primary">
                        Add Room
                    </a>

                </div>
            @else
                <div class="rooms-grid">

                    @foreach ($hotel->rooms as $room)
                        <div class="card room-card">

                            {{-- Room Header --}}

                            <div class="room-header">

                                <div>

                                    <h3>
                                        Room {{ $room->room_number }}
                                    </h3>

                                    @if ($room->name)
                                        <p class="room-name">
                                            {{ $room->name }}
                                        </p>
                                    @endif

                                </div>


                                <span class="room-status room-status-{{ $room->status }}">
                                    {{ ucfirst($room->status) }}
                                </span>

                            </div>


                            {{-- Room Information --}}

                            <div class="room-details">

                                <div class="room-detail">

                                    <span class="detail-label">
                                        Room Number
                                    </span>

                                    <span class="detail-value">
                                        {{ $room->room_number }}
                                    </span>

                                </div>


                                <div class="room-detail">

                                    <span class="detail-label">
                                        Room Type
                                    </span>

                                    <span class="detail-value">
                                        {{ $room->roomType->name }}
                                    </span>

                                </div>


                                <div class="room-detail">

                                    <span class="detail-label">
                                        Status
                                    </span>

                                    <span class="detail-value">
                                        {{ ucfirst($room->status) }}
                                    </span>

                                </div>


                                @if ($room->name)
                                    <div class="room-detail">

                                        <span class="detail-label">
                                            Room Name
                                        </span>

                                        <span class="detail-value">
                                            {{ $room->name }}
                                        </span>

                                    </div>
                                @endif

                            </div>


                            {{-- Actions --}}

                            <div class="room-actions">

                                <a href="{{ route('rooms.edit', [
                                    'hotel' => $hotel->id,
                                    'room' => $room->id,
                                ]) }}"
                                    class="btn">
                                    Edit
                                </a>


                                <form
                                    action="{{ route('rooms.destroy', [
                                        'hotel' => $hotel->id,
                                        'room' => $room->id,
                                    ]) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-alert">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>

            @endif

        </section>

    </main>
</x-owner-layout>
