<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Hotels</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/hotel-home.css') }}">
</head>

<body>
    <header class="navbar">
        <div class="container">
            <a href="/" class="logo">
                <img class="logo-img" src="https://esewahotels.com/images/esewa_hotel_logo_white.svg" alt="esewa">
            </a>
            
            <nav>
                <a href="/">Home</a>
                <a href="/hotels">Hotels</a>
                <a href="/offers">Offers</a>
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
            </nav>
            <div class="auth">
                @guest
                <a href="/login">Login</a>
                <a class="btn" href="/register">Register</a>
                @endguest

                @auth
                <div class="avatar">
                    <p  class='bg-amber-200'>Hello {{auth()->user()->name}}</p>
                    <a href="{{route('user.logout')}}" class="btn">Logout</a>
                </div>
                @endauth
            </div>
        </div>
    </header>
    
    <main>
        {{ $slot }}
    </main>
</body>

</html>
