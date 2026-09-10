<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class OwnerBookingController extends Controller
{
    public function index(){
        $bookings = Booking::with([
            'user',
            'hotel',
            'roomType',
            'payment',
        ])
        ->whereHas('hotel',function($query){
            $query->where('owner_id', Auth::id());
        })
        ->latest()
        ->get();

        return view('bookings.ownerBookings',[
            'bookings'=>$bookings
        ]);
    }
}
