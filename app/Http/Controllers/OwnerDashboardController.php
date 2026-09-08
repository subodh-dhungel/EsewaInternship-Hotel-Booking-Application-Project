<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $ownerId = Auth::id();

        // yo hotel owner ko sabai owned hotel dekhaune
        $hotels = Hotel::where('owner_id', $ownerId)->get();

        //get bookings from owner hotels
        $bookings = Booking::whereHas('hotel', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->get();

        // Get paid bookings
        $paidBookings = $bookings->filter(function ($booking) {
            return $booking->payment_status === 'paid'
                && $booking->payment
                && $booking->payment->status === 'success';
        });

        // Total Revenue
        $totalRevenue = $paidBookings->sum('total_price');

        // This month's revenue
        $monthlyRevenue = $paidBookings->filter(function ($booking) {
            return $booking->created_at->month == now()->month
                && $booking->created_at->year == now()->year;
        })->sum('total_price');

        // Number of paid bookings
        $totalBookings = $paidBookings->count();

        // Hotel performance
        $hotelPerformance = [];

        foreach ($hotels as $hotel) {
            $hotelBookings = $paidBookings->where('hotel_id', $hotel->id);
            $hotelPerformance[] = [
                'hotel' => $hotel,
                'bookings' => $hotelBookings->count(),
                'revenue' => $hotelBookings->sum('total_price')
            ];
        }

        // Recent Bookings
        $recentBookings = Booking::whereHas('hotel', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })
            ->with(['hotel', 'roomType', 'user', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        return view('owner.dashboard', compact(
            'hotels',
            'totalRevenue',
            'monthlyRevenue',
            'totalBookings',
            'hotelPerformance',
            'recentBookings',
        ));
    }
}
