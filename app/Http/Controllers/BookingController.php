<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\RoomTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Hotel $hotel, RoomTypes $room_type)
    {
        $userId = Auth::user()->id;
        // get bookings of current user
        $booking = Booking::where('user_id', $userId)
            ->with('hotel', 'roomType')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.history', [
            'bookings' => $booking,
            'hotel' => $hotel,
            'room_type' => $room_type,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hotel $hotel, RoomTypes $room_type)
    {
        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,
        ]);
    }

    public function checkAvailability(
        Request $request,
        Hotel $hotel,
        RoomTypes $room_type
    ) {
        // Validate the availability data
        $validated = $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'number_of_rooms' => ['required', 'integer', 'min:1'],
            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^9[678]\d{8}$/',
            ],
        ]);

        // Make sure the room type belongs to this hotel
        abort_unless(
            $room_type->hotel_id === $hotel->id,
            404
        );

        // Find the number of rooms already booked
        // during the requested dates
        $bookedRooms = Booking::where('room_type_id', $room_type->id)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->where('check_in', '<', $validated['check_out'])
            ->where('check_out', '>', $validated['check_in'])
            ->sum('number_of_rooms');

        // Calculate available rooms
        $availableRooms = $room_type->total_rooms - $bookedRooms;

        // Return the booking page with availability information
        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,
            'available' => $availableRooms >= $validated['number_of_rooms'],
            'available_rooms' => max(0, $availableRooms),
            'bookingData' => $validated,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request, Hotel $hotel, RoomTypes $room_type)
    {
        // Form bata validated data nikalne
        $validated = $request->validated();

        // Logged in customer ko ID nikalne
        $uid = Auth::id();

        // Room type yo hotel kai ho ki hoina check garne
        abort_unless(
            $room_type->hotel_id === $hotel->id,
            404
        );

        // Check-in ra check-out date lai Carbon date ma convert garne
        $checkIn = Carbon::parse($validated['check-in']);
        $checkOut = Carbon::parse($validated['check-out']);

        // Customer kati raat basne ho calculate garne
        $nights = $checkIn->diffInDays($checkOut);

        // Customer le request gareko room ko number nikalne
        $numberOfRooms = $validated['number_of_rooms'];

        // Requested rooms available cha ki chaina check garne
        if ($numberOfRooms > $room_type->available_rooms) {
            return back()
                ->withErrors([
                    'number_of_rooms' => 'Not enough rooms are available.'
                ])
                ->withInput();
        }

        // Discount price cha bhane discount price use garne
        // Discount price chaina bhane normal price use garne
        $pricePerNight = $room_type->discount_price ?? $room_type->price;

        // Total booking price calculate garne
        $totalPrice = $pricePerNight * $numberOfRooms * $nights;

        // Booking create garne
        $booking = Booking::create([
            // System generated booking number
            'booking_number' => 'BK-' . strtoupper(Str::random(8)),

            // Logged in customer
            'user_id' => $uid,

            // Hotel ra room type
            'hotel_id' => $hotel->id,
            'room_type_id' => $room_type->id,

            // Customer bata aayeko booking information
            'check_in' => $validated['check-in'],
            'check_out' => $validated['check-out'],
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'number_of_rooms' => $numberOfRooms,
            'phone_number' => $validated['phone_number'],

            // Calculated booking price
            'total_price' => $totalPrice,

            // Initial booking status
            'booking_status' => 'pending',

            // Payment initially pending
            'payment_status' => 'pending',
        ]);

        // Booking create bhayepachi booking details page ma jane
        return redirect()
            ->route('bookings.history', $booking)
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show() {}

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
