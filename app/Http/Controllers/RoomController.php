<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    // Hotel vitra ko rooms haru dekhauna ko lagi
    public function index(Hotel $hotel)
    {
        $rooms = $hotel->rooms;

        return view('rooms.index', [
            'hotel' => $hotel,
            'rooms' => $rooms,
        ]);
    }

    // Room create garne page dekhauna ko lagi
    public function create(Hotel $hotel)
    {
        return view('rooms.create', [
            'hotel' => $hotel,
        ]);
    }

    // Create gareko room lai database ma store garna
    public function store(Request $request, Hotel $hotel, RoomTypes $room_types)
    {
        // Form bata aayeko data validate garne
        $validated = $request->validate([
            'room_type_id' => [
                'required',
                'integer',

                Rule::exists('room_types', 'id')
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            'room_number' => [
                'nullable',
                'string',
                'max:50',

                // Eutai hotel bhitra same room number huna nadine
                Rule::unique('rooms', 'room_number')
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            // Room ko optional name validate garne
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Room ko status validate garne
            'status' => [
                'required',
                Rule::in([
                    'available',
                    'maintenance',
                    'inactive',
                ]),
            ],
        ]);

        // Form bata aayeko room type id bata actual room type khojne
        $roomType = RoomTypes::findOrFail($validated['room_type_id']);

        // Room type yo hotel kai ho ki hoina check garne
        abort_unless(
            $roomType->hotel_id === $hotel->id,
            404
        );

        // Room ko hotel_id set garne
        $validated['hotel_id'] = $hotel->id;

        // Room type bhitra physical room create garne
        $roomType->rooms()->create($validated);

        // Room create bhayepachi agadi ko page ma farkine
        return redirect()
            ->back()
            ->with('success', 'Room created successfully.');
    }

    // Edit garna ko lagi room ko existing data dekhaune
    public function edit(Hotel $hotel, Room $room)
    {
        // Room yo hotel kai ho ki hoina check garne
        abort_unless(
            $room->hotel_id === $hotel->id,
            404
        );

        return view('rooms.edit', [
            'hotel' => $hotel,
            'room' => $room,
        ]);
    }

    // Edit gareko room ko data database ma update garna
    public function update(
        Request $request,
        Hotel $hotel,
        Room $room
    ) {
        // Room yo hotel kai ho ki hoina check garne
        abort_unless(
            $room->hotel_id === $hotel->id,
            404
        );

        // Form bata aayeko updated data validate garne
        $validated = $request->validate([
            'room_type_id' => [
                'required',
                'integer',

                // Room type yo hotel kai ho ki hoina check garne
                Rule::exists('room_types', 'id')
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            'room_number' => [
                'required',
                'string',
                'max:50',

                // Same hotel bhitra duplicate room number huna nadine
                // Current room ko room_number chai ignore garne
                Rule::unique('rooms', 'room_number')
                    ->ignore($room->id)
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            // Room ko name optional cha
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Room ko status validate garne
            'status' => [
                'required',
                Rule::in([
                    'available',
                    'maintenance',
                    'inactive',
                ]),
            ],
        ]);

        // Room ko updated data database ma save garne
        $room->update($validated);

        // Update bhayepachi room list ma farkine
        return redirect()
            ->route('rooms.index', [
                'hotel' => $hotel,
            ])
            ->with('success', 'Room updated successfully.');
    }

    // Room lai database bata delete garna
    public function destroy(Hotel $hotel, Room $room)
    {
        // Room yo hotel kai ho ki hoina check garne
        abort_unless(
            $room->hotel_id === $hotel->id,
            404
        );

        // Room delete garne
        $room->delete();

        // Delete bhayepachi room list ma farkine
        return redirect()
            ->route('rooms.index', [
                'hotel' => $hotel,
            ])
            ->with('success', 'Room deleted successfully.');
    }
}