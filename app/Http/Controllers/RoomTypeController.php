<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\RoomTypes;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hotel $hotel)
    {
        return view('room_types.create', [
            'hotel' => $hotel,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'bed_type' => ['required', 'string', 'max:50'],
            'room_size' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lt:price',
            ],
            'total_rooms' => ['required', 'integer', 'min:1'],
            'available_rooms' => [
                'required',
                'integer',
                'min:0',
                'lte:total_rooms',
            ],
        ]);

        // hotel lai room type sanga attach garna ko lagi
        $hotel->roomTypes()->create($validated);

        return redirect(route('owner.hotels.show', [
            'hotel' => $hotel,
        ]))->with(
            'success',
            'room type created successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel, RoomTypes $roomType)
    {
        return view('room_types.edit', [
            'hotel' => $hotel,
            'roomType' => $roomType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel, RoomTypes $room_type)
    {
        // ownership verification
        $roomType = $hotel->roomTypes()->findOrFail($room_type->id);    

        // validation
        $validated = $request->validate([
            'name' => ['nullable','string', 'max:100'],
            'description' => ['nullable','string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'bed_type' => ['nullable', 'string', 'max:50'],
            'room_size' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lt:price',
            ],
            'total_rooms' => ['nullable', 'integer', 'min:1'],
            'available_rooms' => [
                'nullable',
                'integer',
                'min:0',
                'lte:total_rooms',
            ],
        ]);

        $room_type->update($validated);

        //redirect to the previous page
        return redirect(route('owner.hotels.show'))
            ->with('success', 'room type successffully edited');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel, RoomTypes $room_type)
    {
        $room_type->delete();
        return back()->with('success', 'room type deleted successfully');
    }
}
