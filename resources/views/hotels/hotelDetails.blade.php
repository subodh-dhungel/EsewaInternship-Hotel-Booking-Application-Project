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

                <a
                    href="{{ route('hotels.index') }}"
                    class="btn"
                >
                    Back to Hotels
                </a>

                <a
                    href="{{ route('rooms.index', $hotel) }}"
                    class="btn"
                >
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

                                <span
                                    class="stars-filled"
                                    style="width: {{ ($hotel->star_rating / 5) * 100 }}%;"
                                >
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

                <form
                    action="{{ route('owner.hotel-images.store', $hotel) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="image-upload-form"
                >

                    @csrf

                    <div>

                        <label for="hotel-image">
                            Add Hotel Image
                        </label>

                        <input
                            id="hotel-image"
                            class="input"
                            type="file"
                            name="image"
                            accept="image/*"
                            required
                        >

                    </div>

                    <button
                        class="btn"
                        type="submit"
                    >
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

                            <img
                                src="{{ asset('storage/' . $image->image) }}"
                                alt="{{ $hotel->name }}"
                                loading="lazy"
                            >


                            <div class="gallery-item-footer">

                                <form
                                    action="{{ route('owner.hotel-image.destroy', $image) }}"
                                    method="POST"
                                    class="delete-image-form"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-alert"
                                    >
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