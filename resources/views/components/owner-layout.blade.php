<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>eSewa Hotels - Owner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/hotel-owner.css') }}">
</head>

<body>

    <div class="owner-layout">

        <!-- Sidebar -->
        <aside class="owner-sidebar">

            <!-- Logo -->
            <div class="owner-sidebar-logo">

                <a href="{{ route('owner.hotels.index') }}">
                    <img
                        src="https://esewahotels.com/images/esewa_hotel_logo_white.svg"
                        alt="eSewa Hotels"
                    >
                </a>

            </div>


            <!-- Navigation -->
            <nav class="owner-sidebar-nav">

                <a href="{{route('owner.index')}}" class="owner-nav-item">
                    <span class="owner-nav-icon">▦</span>
                    <span>Dashboard</span>
                </a>

                <a
                    href="{{ route('owner.hotels.index') }}"
                    class="owner-nav-item"
                >
                    <span class="owner-nav-icon">⌂</span>
                    <span>My Hotels</span>
                </a>

                <a href="#" class="owner-nav-item">
                    <span class="owner-nav-icon">▤</span>
                    <span>Rooms</span>
                </a>

                <a href="#" class="owner-nav-item">
                    <span class="owner-nav-icon">▣</span>
                    <span>Bookings</span>
                </a>

                <a href="#" class="owner-nav-item">
                    <span class="owner-nav-icon">★</span>
                    <span>Reviews</span>
                </a>

            </nav>


            <!-- Bottom Navigation -->
            <div class="owner-sidebar-bottom">

                <a href="#" class="owner-nav-item">
                    <span class="owner-nav-icon">⚙</span>
                    <span>Settings</span>
                </a>

                <a
                    href="{{ route('user.logout') }}"
                    class="owner-nav-item owner-logout"
                >
                    <span class="owner-nav-icon">↪</span>
                    <span>Logout</span>
                </a>

            </div>

        </aside>


        <!-- Main Content -->
        <div class="owner-main">

            <!-- Top Bar -->
            <header class="owner-topbar">

                <div class="owner-page-title">
                    <h1>Hotel Owner</h1>
                </div>

                <div class="owner-user">

                    <div class="owner-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="owner-user-info">
                        <span class="owner-user-name">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="owner-user-role">
                            Hotel Owner
                        </span>
                    </div>

                </div>

            </header>


            <!-- Page Content -->
            <main class="owner-content">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>

</html>