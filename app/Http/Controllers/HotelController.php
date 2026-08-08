<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use BcMath\Number;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::with('roomTypes')->get();
        return view('hotels.hotelList', ['hotels'=>$hotels]);
    }

    public function featured() {
        $featuredHotels = Hotel::where('is_featured',true)->get();
        return view('index', ['featuredHotels'=>$featuredHotels]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //$hotel->all()->get();
        //$hotel->load();
        $hotel = Hotel::findOrFail($id);
        return view('hotels.hotelDetails', ['hotel'=>$hotel]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
