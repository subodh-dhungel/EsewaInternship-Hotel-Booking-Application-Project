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
    public function store(Request $request, Hotel $hotel)
    {
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

                Rule::unique('rooms', 'room_number')
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'occupied',
                    'maintenance',
                    'inactive',
                ]),
            ],
        ]);

        // Form bata aayeko room type khojne
        $roomType = RoomTypes::findOrFail($validated['room_type_id']);

        // Room type yo hotel kai ho ki hoina check garne
        abort_unless(
            $roomType->hotel_id === $hotel->id,
            404
        );

        // Room ko hotel_id set garne
        $validated['hotel_id'] = $hotel->id;

        // Physical room create garne
        $roomType->rooms()->create($validated);

        // Total physical rooms
        $totalRooms = $roomType->rooms()->count();

        // Only available rooms
        $availableRooms = $roomType->rooms()
            ->where('status', 'available')
            ->count();

        // Room type ko inventory update garne
        $roomType->update([
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Room created successfully.');
    }

    // Edit garna ko lagi room ko existing data dekhaune
    public function edit(Hotel $hotel, Room $room)
    {
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
        abort_unless(
            $room->hotel_id === $hotel->id,
            404
        );

        // Purano room type
        $oldRoomType = $room->roomType;

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
                'required',
                'string',
                'max:50',

                Rule::unique('rooms', 'room_number')
                    ->ignore($room->id)
                    ->where(function ($query) use ($hotel) {
                        $query->where('hotel_id', $hotel->id);
                    }),
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'occupied',
                    'maintenance',
                    'inactive',
                ]),
            ],
        ]);

        // Room update garne
        $room->update($validated);

        // Naya room type
        $newRoomType = $room->roomType;

        /*
        |--------------------------------------------------------------------------
        | Purano room type update
        |--------------------------------------------------------------------------
        */

        $oldRoomType->update([
            'total_rooms' => $oldRoomType->rooms()->count(),

            'available_rooms' => $oldRoomType->rooms()
                ->where('status', 'available')
                ->count(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Naya room type update
        |--------------------------------------------------------------------------
        */

        $newRoomType->update([
            'total_rooms' => $newRoomType->rooms()->count(),

            'available_rooms' => $newRoomType->rooms()
                ->where('status', 'available')
                ->count(),
        ]);

        return redirect()
            ->route('rooms.index', [
                'hotel' => $hotel,
            ])
            ->with('success', 'Room updated successfully.');
    }

    // Room lai database bata delete garna
    public function destroy(Hotel $hotel, Room $room)
    {
        abort_unless(
            $room->hotel_id === $hotel->id,
            404
        );

        // Delete garnu agadi room type store garne
        $roomType = $room->roomType;

        // Room delete garne
        $room->delete();

        // Total physical rooms
        $totalRooms = $roomType->rooms()->count();

        // Only available rooms
        $availableRooms = $roomType->rooms()
            ->where('status', 'available')
            ->count();

        // Room type inventory update garne
        $roomType->update([
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
        ]);

        return redirect()
            ->route('rooms.index', [
                'hotel' => $hotel,
            ])
            ->with('success', 'Room deleted successfully.');
    }
}
