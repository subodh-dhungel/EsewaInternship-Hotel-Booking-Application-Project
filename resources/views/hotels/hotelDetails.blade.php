<x-layout>



    <div class="Hoteldescription card">
        <h1>Hotel Description</h1>
        <p><strong>Name : </strong> {{ $hotel->name }}</p>
        <p><strong>Description : </strong> {{ $hotel->description }}</p>
        <p><strong>Address : </strong> {{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->district }},
            {{ $hotel->country }}</p>
        <p><strong>GPS Coordinates</strong> {{ $hotel->latitude }} °N {{ $hotel->longitude }} °E</p>

        <div class="rating">
            <p class="text">rating: </p>
            <div class="stars"> <span class="stars-empty">★★★★★</span> <span class="stars-filled"
                    style="width: {{ ($hotel->star_rating / 5) * 100 }}%;"> ★★★★★ </span> </div>
            <span class="rating-number"> {{ number_format($hotel->star_rating, 1) }} </span>
        </div>

        <p><strong>phone: </strong>{{ $hotel->phone }}</p>
        <p><strong>email: </strong>{{ $hotel->email }}</p>
        <p><strong>Checkin time: </strong>{{ $hotel->checkin_time }}</p>
        <p><strong>Checkout time: </strong>{{ $hotel->check_out_time }}</p>
        <p><strong>Listing date: </strong>{{ $hotel->created_at }}</p>

        <form action="#">
            <input onclick="" type="submit">
        </form>
    </div>

</x-layout>
