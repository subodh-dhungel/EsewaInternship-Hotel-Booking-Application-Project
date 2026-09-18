<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display featured hotels on the homepage.
     */
    public function featured()
    {
        $featuredHotels = Hotel::with(['roomTypes', 'amenity'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->get();

        return view('index', [
            'featuredHotels' => $featuredHotels,
        ]);
    }


    /**
     * Display hotels available for customers.
     */
    public function index(Request $request)
    {
        $location = $request->input('location');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $guests = $request->input('guests');

        $hotels = Hotel::with([
            'roomTypes',
            'amenity',
            'city',
        ])
            ->where('status', 'active')
            ->when($location, function ($query) use ($location) {
                $query->where(function ($query) use ($location) {
                    $query->where('name', 'like', '%' . $location . '%')
                        ->orWhereHas('city', function ($query) use ($location) {
                            $query->where('name', 'like', '%' . $location . '%');
                        });
                });
            })
            ->get();

        return view('hotels.hotelList', [
            'hotels' => $hotels,
            'location' => $location,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
        ]);
    }


    /**
     * Display a hotel.
     */
    public function show(Hotel $hotel)
    {
        // Customers should only be able to view active hotels.
        abort_if($hotel->status !== 'active', 404);

        $hotel->load([
            'image',
            'roomTypes',
            'amenity',
        ]);

        return view('hotels.customerHotelDetails', [
            'hotel' => $hotel,
        ]);
    }
}
