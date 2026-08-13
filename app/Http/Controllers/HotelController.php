<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return view('hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>['required', 'string', 'max:255'],
            'description'=>['required','string'],
            'address'=>['required','string','max:255'],
            'city'=>['required','string','max:100'],
            'district'=>['required','string','max:100'],
            'country'=>['required','string','max:100'],
            'latitude'=>['nullable','numeric'],
            'longitude'=>['nullable', 'numeric'],
            'star_rating'=>['required','integer','min:1','max:5'],
            'phone'=>['required','string','max:20'],
            'email'=>['required','email','max:255'],
            'checkin_time'=> ['required','date_format:H:i'],
            'check_out_time'=>['required', 'date_format:H:i'],
            'featured_image'=>['required','image','max:5120'],
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured']=false;
        $validated['status']='pending';

        $imagePath = $request->file('featured_image')->store('hotel-images', 'public');
        $validated['featured_image'] = $imagePath;
        $hotel = Hotel::create($validated);

        return redirect()
            ->route('hotels.show',$hotel->id)
            ->with('success','Hotel created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
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
