<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Hotels</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="navbar">
        <div class="container navbar-inner">
            <a href="/" class="logo">
                <img class="logo-img" src="https://esewahotels.com/images/esewa_hotel_logo_white.svg" alt="esewa">
            </a>

            <button class="mobile-menu-button" type="button" aria-label="Open navigation" aria-controls="main-navigation-menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <div class="navbar-menu" id="main-navigation-menu">
                <nav class="main-navigation" aria-label="Main navigation">
                    <a href="{{route('hotels.featured')}}">Home</a>
                    <a href="{{route('hotels.index')}}">Hotels</a>
                    <a href="/offers">Offers</a>
                    <a href="{{route('bookings.history')}}">My bookings</a>
                    <a href="/contact">Contact</a>
                </nav>
                <div class="auth">
                    @guest
                    <a href="/login">Login</a>
                    <a class="btn" href="/register">Register</a>
                    @endguest

                    @auth
                    <div class="avatar">
                        <p class='bg-amber-200'>Hello {{auth()->user()->name}}</p>
                        <a href="{{route('user.logout')}}" class="btn">Logout</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <main>
        @if (session('success') || session('error') || session('warning') || session('info'))
            <div class="flash-messages" role="status" aria-live="polite">
                @foreach (['success', 'error', 'warning', 'info'] as $flashType)
                    @if (session($flashType))
                        <div class="flash-message flash-message--{{ $flashType }}">
                            <span class="flash-message__icon" aria-hidden="true">
                                {{ $flashType === 'success' ? '✓' : ($flashType === 'error' ? '!' : 'i') }}
                            </span>
                            <p>{{ session($flashType) }}</p>
                            <button type="button" class="flash-message__close" aria-label="Dismiss message">&times;</button>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        {{ $slot }}
    </main>
</body>

</html>
