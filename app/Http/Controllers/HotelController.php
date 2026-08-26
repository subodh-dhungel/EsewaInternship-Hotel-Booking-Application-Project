<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

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
    public function index()
    {
        $hotels = Hotel::with([
                'roomTypes',
                'amenity',
            ])
            ->where('status', 'active')
            ->get();

        return view('hotels.hotelList', [
            'hotels' => $hotels,
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