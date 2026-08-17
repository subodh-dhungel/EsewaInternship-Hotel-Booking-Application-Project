<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('customer')) {

            $hotels = Hotel::with(['roomTypes', 'amenity'])
                ->where('status', 'active')
                ->get();

            return view('hotels.hotelList', [
                'hotels' => $hotels,
            ]);
        }

        if ($user->hasRole('hotel_owner')) {

            $activeHotels = Hotel::with(['roomTypes', 'amenity'])
                ->where('owner_id', $user->id)
                ->where('status', 'active')
                ->get();

            $pendingHotels = Hotel::with(['roomTypes', 'amenity'])
                ->where('owner_id', $user->id)
                ->where('status', 'pending')
                ->get();

            $inactiveHotels = Hotel::with(['roomTypes', 'amenity'])
                ->where('owner_id', $user->id)
                ->where('status', 'inactive')
                ->get();

            return view('hotels.hotelList', [
                'activeHotels' => $activeHotels,
                'pendingHotels' => $pendingHotels,
                'inactiveHotels' => $inactiveHotels,
            ]);
        }
    }

    public function featured()
    {
        $featuredHotels = Hotel::where('is_featured', true)->get();
        return view('index', ['featuredHotels' => $featuredHotels]);
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'star_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'checkin_time' => ['required', 'date_format:H:i'],
            'check_out_time' => ['required', 'date_format:H:i'],
            'featured_image' => ['required', 'image', 'max:5120'],
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = false;
        $validated['status'] = 'pending';

        $imagePath = $request->file('featured_image')->store('hotel-images', 'public');
        $validated['featured_image'] = $imagePath;
        $hotel = Hotel::create($validated);

        return redirect()
            ->route('hotels.show', $hotel->id)
            ->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->load('image');
        return view('hotels.hotelDetails', ['hotel' => $hotel]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        // dd($hotel);
        $price = RoomTypes::where('hotel_id', $hotel->id)->value('price');
        return view('hotels.edit', [
            'hotel' => $hotel,
            'price' => $price,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'star_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'checkin_time' => ['nullable', 'date_format:H:i:s'],
            'check_out_time' => ['nullable', 'date_format:H:i:s'],
            'featured_image' => ['nullable', 'image'],
        ]);

        // User le naya file upload gareko cha ki chaina check garna ko lagi

        if ($request->hasFile('featured_image')) {
            //Delete old image
            if ($hotel->featured_image) {
                $imagePath = $hotel->featured_image;
                Storage::disk('public')->delete($imagePath);
            }

            //insert new image
            $validated['featured_image'] = $request->file('featured_image')->store('hotel-images', 'public');
        }

        $hotel->update($validated);

        return redirect()
            ->route('hotels.index', $hotel)
            ->with('Success', 'Hotel updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function set_inactive(Hotel $hotel)
    {
        $hotel->update([
            'status' => 'inactive',
        ]);

        return redirect(route('hotels.hotelDetails.php'));
    }

    public function set_active(Hotel $hotel)
    {
        $hotel->update([
            'status' => 'active',
        ]);

        return redirect(route('hotels.hotelDetails.php'));
    }
}
