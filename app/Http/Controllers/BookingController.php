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
     * Display the customer's booking history.
     */
    public function index()
    {
        $bookings = Booking::with(['hotel', 'roomType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.history', [
            'bookings' => $bookings,
        ]);
    }


    /**
     * Display the booking form.
     */
    public function create(Hotel $hotel, RoomTypes $room_type)
    {
        abort_unless(
            $room_type->hotel_id === $hotel->id,
            404
        );

        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,
        ]);
    }


    /**
     * Check room availability for the requested dates.
     */
    public function checkAvailability(
        Request $request,
        Hotel $hotel,
        RoomTypes $room_type
    ) {
        // Validate availability data
        $validated = $request->validate([
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],

            'adults' => [
                'required',
                'integer',
                'min:1',
            ],

            'children' => [
                'required',
                'integer',
                'min:0',
            ],

            'number_of_rooms' => [
                'required',
                'integer',
                'min:1',
            ],

            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^9[678]\d{8}$/',
            ],
        ]);


        // Make sure this room type belongs to this hotel
        abort_unless(
            $room_type->hotel_id === $hotel->id,
            404
        );


        // Find rooms already booked during the requested dates
        $bookedRooms = Booking::where(
                'room_type_id',
                $room_type->id
            )
            ->whereIn(
                'booking_status',
                ['pending', 'confirmed']
            )
            ->where(
                'check_in',
                '<',
                $validated['check_out']
            )
            ->where(
                'check_out',
                '>',
                $validated['check_in']
            )
            ->sum('number_of_rooms');


        // Calculate remaining rooms
        $availableRooms = $room_type->total_rooms - $bookedRooms;


        // Return the booking page with the result
        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,

            'available' =>
                $availableRooms >= $validated['number_of_rooms'],

            'available_rooms' =>
                max(0, $availableRooms),

            'bookingData' => $validated,
        ]);
    }


    /**
     * Store a new booking.
     */
    public function store(
        StoreBookingRequest $request,
        Hotel $hotel,
        RoomTypes $room_type
    ) {
        // Get validated data from FormRequest
        $validated = $request->validated();


        // Make sure the room type belongs to this hotel
        abort_unless(
            $room_type->hotel_id === $hotel->id,
            404
        );


        // Convert dates to Carbon
        $checkIn = Carbon::parse(
            $validated['check_in']
        );

        $checkOut = Carbon::parse(
            $validated['check_out']
        );


        // Calculate number of nights
        $nights = $checkIn->diffInDays($checkOut);


        // Requested number of rooms
        $numberOfRooms = $validated['number_of_rooms'];


        /*
         * ---------------------------------------------------------
         * FINAL AVAILABILITY CHECK
         * ---------------------------------------------------------
         *
         * We check availability again here because the availability
         * may have changed after the customer initially checked it.
         */

        $bookedRooms = Booking::where(
                'room_type_id',
                $room_type->id
            )
            ->whereIn(
                'booking_status',
                ['pending', 'confirmed']
            )
            ->where(
                'check_in',
                '<',
                $validated['check_out']
            )
            ->where(
                'check_out',
                '>',
                $validated['check_in']
            )
            ->sum('number_of_rooms');


        // Calculate the latest available inventory
        $availableRooms = $room_type->total_rooms - $bookedRooms;


        // Stop booking if there aren't enough rooms
        if ($numberOfRooms > $availableRooms) {

            return back()
                ->withErrors([
                    'number_of_rooms' =>
                        "Only {$availableRooms} room(s) are available for the selected dates.",
                ])
                ->withInput();
        }


        /*
         * ---------------------------------------------------------
         * CALCULATE PRICE
         * ---------------------------------------------------------
         */

        $pricePerNight =
            $room_type->discount_price
            ?? $room_type->price;


        $totalPrice =
            $pricePerNight
            * $numberOfRooms
            * $nights;


        /*
         * ---------------------------------------------------------
         * CREATE BOOKING
         * ---------------------------------------------------------
         */

        $booking = Booking::create([

            'booking_number' =>
                'BK-' . strtoupper(Str::random(8)),

            'user_id' =>
                Auth::id(),

            'hotel_id' =>
                $hotel->id,

            'room_type_id' =>
                $room_type->id,

            'check_in' =>
                $validated['check_in'],

            'check_out' =>
                $validated['check_out'],

            'adults' =>
                $validated['adults'],

            'children' =>
                $validated['children'],

            'number_of_rooms' =>
                $numberOfRooms,

            'phone_number' =>
                $validated['phone_number'],

            'total_price' =>
                $totalPrice,

            'booking_status' =>
                'pending',

            'payment_status' =>
                'pending',
        ]);


        /*
         * ---------------------------------------------------------
         * REDIRECT TO BOOKING HISTORY
         * ---------------------------------------------------------
         */

        return redirect()
            ->route('bookings.history')
            ->with(
                'success',
                'Booking created successfully.'
            );
    }


    /**
     * Show a single booking.
     */
    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id(),
            403
        );

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }


    /**
     * Edit booking.
     */
    public function edit(Booking $booking)
    {
        //
    }


    /**
     * Update booking.
     */
    public function update(
        Request $request,
        Booking $booking
    ) {
        //
    }


    /**
     * Cancel/delete booking.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}