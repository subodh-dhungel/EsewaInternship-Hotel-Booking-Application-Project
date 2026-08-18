<x-layout>

    <div class="Hoteldescription card">

        <h1>Hotel Description</h1>

        <p>
            <strong>Name : </strong>
            {{ $hotel->name }}
        </p>

        <p>
            <strong>Description : </strong>
            {{ $hotel->description }}
        </p>

        <p>
            <strong>Address : </strong>
            {{ $hotel->address }},
            {{ $hotel->city }},
            {{ $hotel->district }},
            {{ $hotel->country }}
        </p>

        <p>
            <strong>GPS Coordinates : </strong>
            {{ $hotel->latitude }} °N
            {{ $hotel->longitude }} °E
        </p>


        {{-- Rating --}}
        <div class="rating">

            <p class="text">Rating:</p>

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


        <p>
            <strong>Phone : </strong>
            {{ $hotel->phone }}
        </p>

        <p>
            <strong>Email : </strong>
            {{ $hotel->email }}
        </p>

        <p>
            <strong>Check-in time : </strong>
            {{ $hotel->checkin_time }}
        </p>

        <p>
            <strong>Check-out time : </strong>
            {{ $hotel->check_out_time }}
        </p>

        <p>
            <strong>Listing date : </strong>
            {{ $hotel->created_at }}
        </p>


        {{-- =========================================
             HOTEL GALLERY
             ========================================= --}}

        <h2>Hotel Gallery</h2>


        {{-- Image Upload --}}
        <div class="hotel-image-upload">

            <form
                action="{{ route('hotel-images.store', $hotel) }}"
                method="POST"
                enctype="multipart/form-data"
                class="image-upload-form"
            >

                @csrf

                <input
                    class="input"
                    type="file"
                    name="image"
                    accept="image/*"
                    required
                >

                <button
                    class="btn"
                    type="submit"
                >
                    Upload Image
                </button>

            </form>

        </div>


        {{-- =========================================
             GALLERY IMAGES
             ========================================= --}}

        @if ($hotel->image->isEmpty())

            <p class="empty-gallery">
                There are no hotel images to show here.
            </p>

        @else

            {{-- THIS IS THE GRID CONTAINER --}}
            <div class="hotel-gallery">

                @foreach ($hotel->image as $image)

                    {{-- ONE GRID ITEM --}}
                    <div class="gallery-item">

                        {{-- Image --}}
                        <img
                            src="{{ asset('storage/' . $image->image) }}"
                            alt="{{ $hotel->name }}"
                        >


                        {{-- Delete --}}
                        <form
                            action="{{ route('hotel-image.destroy', $image) }}"
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

                @endforeach

            </div>

        @endif

    </div>

</x-layout>