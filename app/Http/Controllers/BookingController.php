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
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request,Hotel $hotel,RoomTypes $room_type) {
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
        $totalPrice = $pricePerNight
            * $numberOfRooms
            * $nights;

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
            ->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('bookings.history');
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
