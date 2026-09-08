<x-owner-layout>

    <div class="owner-dashboard">

        {{-- ================================
             DASHBOARD HEADER
        ================================= --}}

        <div class="dashboard-header">

            <div>
                <p class="dashboard-eyebrow">
                    HOTEL MANAGEMENT
                </p>

                <h1>
                    Owner Dashboard
                </h1>

                <p class="dashboard-subtitle">
                    Monitor your hotels, bookings, and revenue from one place.
                </p>
            </div>

            <div class="dashboard-date">
                <span class="dashboard-date-label">
                    Today
                </span>

                <span class="dashboard-date-value">
                    {{ now()->format('M d, Y') }}
                </span>
            </div>

        </div>


        {{-- ================================
             SUMMARY CARDS
        ================================= --}}

        <div class="dashboard-stats">

            {{-- Total Revenue --}}
            <div class="dashboard-stat-card revenue-card">

                <div class="stat-card-top">

                    <div class="stat-icon">
                        Rs
                    </div>

                    <span class="stat-label">
                        Total Revenue
                    </span>

                </div>

                <div class="stat-value">
                    Rs. {{ number_format($totalRevenue, 2) }}
                </div>

                <div class="stat-description">
                    Revenue from successful payments
                </div>

            </div>


            {{-- Monthly Revenue --}}
            <div class="dashboard-stat-card monthly-card">

                <div class="stat-card-top">

                    <div class="stat-icon">
                        ↑
                    </div>

                    <span class="stat-label">
                        This Month
                    </span>

                </div>

                <div class="stat-value">
                    Rs. {{ number_format($monthlyRevenue, 2) }}
                </div>

                <div class="stat-description">
                    Revenue generated this month
                </div>

            </div>


            {{-- Paid Bookings --}}
            <div class="dashboard-stat-card bookings-card">

                <div class="stat-card-top">

                    <div class="stat-icon">
                        #
                    </div>

                    <span class="stat-label">
                        Paid Bookings
                    </span>

                </div>

                <div class="stat-value">
                    {{ number_format($totalBookings) }}
                </div>

                <div class="stat-description">
                    Successfully paid reservations
                </div>

            </div>


            {{-- Hotels --}}
            <div class="dashboard-stat-card hotels-card">

                <div class="stat-card-top">

                    <div class="stat-icon">
                        H
                    </div>

                    <span class="stat-label">
                        Your Hotels
                    </span>

                </div>

                <div class="stat-value">
                    {{ $hotels->count() }}
                </div>

                <div class="stat-description">
                    Hotels under your management
                </div>

            </div>

        </div>


        {{-- ================================
             HOTEL PERFORMANCE
        ================================= --}}

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>
                    <h2>
                        Hotel Performance
                    </h2>

                    <p>
                        See how much revenue each of your hotels is generating.
                    </p>
                </div>

                <div class="section-badge">
                    {{ $hotels->count() }} Hotels
                </div>

            </div>


            <div class="dashboard-table-wrapper">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>
                                Hotel
                            </th>

                            <th>
                                Paid Bookings
                            </th>

                            <th>
                                Revenue
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($hotelPerformance as $performance)

                            <tr>

                                <td>

                                    <div class="hotel-table-name">

                                        <div class="hotel-table-icon">
                                            H
                                        </div>

                                        <div>

                                            <div class="hotel-name">
                                                {{ $performance['hotel']->name }}
                                            </div>

                                            <div class="hotel-city">
                                                {{ $performance['hotel']->city }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span class="booking-count">
                                        {{ number_format($performance['bookings']) }}
                                    </span>

                                </td>


                                <td>

                                    <span class="revenue-value">
                                        Rs. {{ number_format($performance['revenue'], 2) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3">

                                    <div class="dashboard-empty-state">

                                        <div class="empty-icon">
                                            H
                                        </div>

                                        <h3>
                                            No hotel data yet
                                        </h3>

                                        <p>
                                            Your hotel performance will appear here once you receive bookings.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- ================================
             RECENT BOOKINGS
        ================================= --}}

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>
                    <h2>
                        Recent Bookings
                    </h2>

                    <p>
                        The latest reservations across all your hotels.
                    </p>
                </div>

                <div class="section-badge">
                    Latest
                </div>

            </div>


            <div class="dashboard-table-wrapper">

                <table class="dashboard-table recent-bookings-table">

                    <thead>

                        <tr>

                            <th>
                                Guest
                            </th>

                            <th>
                                Hotel
                            </th>

                            <th>
                                Check In
                            </th>

                            <th>
                                Check Out
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Payment
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentBookings as $booking)

                            <tr>

                                {{-- Guest --}}
                                <td>

                                    <div class="guest-info">

                                        <div class="guest-avatar">
                                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                        </div>

                                        <div>

                                            <div class="guest-name">
                                                {{ $booking->user->name }}
                                            </div>

                                            <div class="booking-number">
                                                {{ $booking->booking_number }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Hotel --}}
                                <td>

                                    <span class="table-hotel-name">
                                        {{ $booking->hotel->name }}
                                    </span>

                                </td>


                                {{-- Check In --}}
                                <td>

                                    <span class="date-value">
                                        {{ $booking->check_in->format('M d, Y') }}
                                    </span>

                                </td>


                                {{-- Check Out --}}
                                <td>

                                    <span class="date-value">
                                        {{ $booking->check_out->format('M d, Y') }}
                                    </span>

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <span class="amount-value">
                                        Rs. {{ number_format($booking->total_price, 2) }}
                                    </span>

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @if($booking->payment_status === 'paid')

                                        <span class="payment-badge payment-paid">
                                            <span class="payment-dot"></span>
                                            Paid
                                        </span>

                                    @elseif($booking->payment_status === 'pending')

                                        <span class="payment-badge payment-pending">
                                            <span class="payment-dot"></span>
                                            Pending
                                        </span>

                                    @else

                                        <span class="payment-badge payment-failed">
                                            <span class="payment-dot"></span>
                                            Failed
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="dashboard-empty-state">

                                        <div class="empty-icon">
                                            #
                                        </div>

                                        <h3>
                                            No bookings yet
                                        </h3>

                                        <p>
                                            New reservations will appear here.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </div>

</x-owner-layout>