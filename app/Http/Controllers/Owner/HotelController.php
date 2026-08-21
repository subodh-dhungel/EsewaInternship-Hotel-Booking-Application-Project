<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    /**
     * Display hotels belonging to the authenticated owner.
     */
    public function index()
    {
        $user = Auth::user();

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

        return view('hotels.ownerHotelList', [
            'activeHotels' => $activeHotels,
            'pendingHotels' => $pendingHotels,
            'inactiveHotels' => $inactiveHotels,
        ]);
    }

    public function dashboard()
    {
        return view('hotels.ownerDashboard');
    }


    /**
     * Show the form for creating a new hotel.
     */
    public function create()
    {
        return view('hotels.create');
    }


    /**
     * Store a newly created hotel.
     */
    public function store(Request $request)
    {
        Log::info('HOTEL STORE CALLED', [
            'time' => now()->toDateTimeString(),
            'user_id' => Auth::id(),
        ]);

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
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['owner_id'] = Auth::id();

        // Generate unique slug
        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $count = 1;
        while (Hotel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $validated['slug'] = $slug;

        $validated['is_featured'] = false;
        $validated['status'] = 'pending';

        // Store featured image
        $validated['featured_image'] = $request
            ->file('featured_image')
            ->store('hotel-images', 'public');

        // Remove price because price belongs to room_types
        $price = $validated['price'];
        unset($validated['price']);

        // Create hotel
        $hotel = Hotel::create($validated);

        // Create default room type
        RoomTypes::create([
            'hotel_id' => $hotel->id,
            'name' => 'Standard Room',
            'description' => 'Standard room at this hotel',
            'price' => $price,
            'discount_price' => 200,
            'capacity' => 2,
            'bed_type' => 'king',
            'room_size' => 100,
            'total_rooms' => 10,
            'available_rooms' => 9,
        ]);

        return redirect()
            ->route('owner.hotels.show', $hotel->id)
            ->with('success', 'Hotel created successfully.');
    }


    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        Gate::authorize('view', $hotel);

        $hotel->load('image');

        return view('hotels.hotelDetails', [
            'hotel' => $hotel,
        ]);
    }


    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel)
    {
        Gate::authorize('update', $hotel);
        $price = RoomTypes::where('hotel_id', $hotel->id)
            ->value('price');
        return view('hotels.edit', [
            'hotel' => $hotel,
            'price' => $price,
        ]);
    }


    /**
     * Update the specified hotel.
     */
    public function update(Request $request, Hotel $hotel)
    {
        Gate::authorize('update', $hotel);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'star_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'checkin_time' => ['required', 'date_format:H:i:s'],
            'check_out_time' => ['required', 'date_format:H:i:s'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        /*
    |--------------------------------------------------------------------------
    | Update hotel
    |--------------------------------------------------------------------------
    */

        $hotel->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'country' => $validated['country'],
            'star_rating' => $validated['star_rating'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'checkin_time' => $validated['checkin_time'],
            'check_out_time' => $validated['check_out_time'],
        ]);


        /*
    |--------------------------------------------------------------------------
    | Update featured image
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('featured_image')) {

            if ($hotel->featured_image) {
                Storage::disk('public')->delete(
                    $hotel->featured_image
                );
            }

            $imagePath = $request
                ->file('featured_image')
                ->store('hotel-images', 'public');

            $hotel->update([
                'featured_image' => $imagePath,
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | Update room price
    |--------------------------------------------------------------------------
    */

        $roomType = RoomTypes::where('hotel_id', $hotel->id)
            ->first();

        if ($roomType) {

            $roomType->update([
                'price' => $validated['price'],
            ]);
        }


        return redirect()
            ->route('owner.hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }


    /**
     * Deactivate hotel.
     */
    public function set_inactive(Hotel $hotel)
    {
        Gate::authorize('deactivate', $hotel);

        $hotel->update([
            'status' => 'inactive',
        ]);

        return redirect()
            ->route('owner.hotels.index')
            ->with('success', 'Hotel deactivated successfully.');
    }


    /**
     * Activate hotel.
     */
    public function set_active(Hotel $hotel)
    {
        Gate::authorize('activate', $hotel);

        $hotel->update([
            'status' => 'active',
        ]);

        return redirect()
            ->route('owner.hotels.index')
            ->with('success', 'Hotel activated successfully.');
    }
}
