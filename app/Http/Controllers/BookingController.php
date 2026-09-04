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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            (int) $room_type->hotel_id === (int) $hotel->id,
            404
        );

        abort_unless(
            $hotel->status === 'active',
            404
        );

        $availableRooms = null;
        $checkIn = old('check_in');
        $checkOut = old('check_out');

        if ($checkIn && $checkOut) {
            try {
                $checkInDate = Carbon::parse($checkIn);
                $checkOutDate = Carbon::parse($checkOut);

                if ($checkOutDate->greaterThan($checkInDate)) {
                    $availableRooms = $this->availableRooms(
                        $room_type,
                        $checkInDate->toDateString(),
                        $checkOutDate->toDateString()
                    );
                }
            } catch (\Exception $exception) {
                $availableRooms = null;
            }
        }

        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,
            'available_rooms' => $availableRooms,
            'available' => $availableRooms !== null
                ? $availableRooms > 0
                : null,
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
        abort_unless(
            (int) $room_type->hotel_id === (int) $hotel->id,
            404
        );

        abort_unless(
            $hotel->status === 'active',
            404
        );

        $validated = $request->validate([
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in'
            ],
            'adults' => [
                'required',
                'integer',
                'min:1'
            ],
            'children' => [
                'required',
                'integer',
                'min:0'
            ],
            'number_of_rooms' => [
                'required',
                'integer',
                'min:1'
            ],
            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^9[678]\d{8}$/',
            ],
        ]);

        $this->validateCapacity($room_type, $validated);

        $availableRooms = $this->availableRooms(
            $room_type,
            $validated['check_in'],
            $validated['check_out']
        );

        return view('bookings.create', [
            'hotel' => $hotel,
            'room_type' => $room_type,
            'available' => $availableRooms >= $validated['number_of_rooms'],
            'available_rooms' => max(0, $availableRooms),
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
        abort_unless(
            (int) $room_type->hotel_id === (int) $hotel->id,
            404
        );

        $validated = $request->validated();

        $booking = DB::transaction(function () use (
            $validated,
            $hotel,
            $room_type
        ) {

            // Lock room type before checking availability
            $lockedRoomType = RoomTypes::whereKey($room_type->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Check if hotel is available
            if ($hotel->status !== 'active') {
                throw ValidationException::withMessages([
                    'hotel' => 'This hotel is not currently available for booking.',
                ]);
            }

            // Make sure room type belongs to the selected hotel
            if ((int) $lockedRoomType->hotel_id !== (int) $hotel->id) {
                abort(404);
            }

            // Check guest capacity
            $this->validateCapacity(
                $lockedRoomType,
                $validated
            );

            // Check room availability
            $availableRooms = $this->availableRooms(
                $lockedRoomType,
                $validated['check_in'],
                $validated['check_out']
            );

            $requestedRooms = (int) $validated['number_of_rooms'];

            if ($requestedRooms > $availableRooms) {
                throw ValidationException::withMessages([
                    'number_of_rooms' =>
                        "Only {$availableRooms} room(s) are available for the selected dates.",
                ]);
            }

            // Calculate total price
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);

            $nights = $checkIn->diffInDays($checkOut);

            $pricePerNight = $lockedRoomType->discount_price
                ?? $lockedRoomType->price;

            $totalPrice = $pricePerNight
                * $requestedRooms
                * $nights;

            // Create booking
            return Booking::create([
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),

                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
                'room_type_id' => $lockedRoomType->id,

                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],

                'adults' => $validated['adults'],
                'children' => $validated['children'],

                'number_of_rooms' => $requestedRooms,
                'phone_number' => $validated['phone_number'],

                'total_price' => $totalPrice,

                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'expires_at'=>now()->addMinutes(15),
            ]);
        });

        return redirect()
            ->route('payments.initiate', $booking);
            
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
     * Cancel booking.
     */
    public function destroy(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id(),
            403
        );

        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            throw ValidationException::withMessages([
                'booking' => 'This booking cannot be cancelled.',
            ]);
        }

        $booking->update([
            'booking_status' => 'cancelled',
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Calculate available rooms for the selected dates.
     */
    private function availableRooms(
        RoomTypes $roomType,
        string $checkIn,
        string $checkOut
    ): int {
        $bookedRooms = Booking::query()
            ->where('room_type_id', $roomType->id)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->sum('number_of_rooms');

        return max(
            0,
            (int) $roomType->total_rooms - (int) $bookedRooms
        );
    }

    /**
     * Validate room capacity.
     */
    private function validateCapacity(
        RoomTypes $roomType,
        array $data
    ): void {
        $totalGuests = (int) $data['adults']
            + (int) $data['children'];

        $maximumGuests = (int) $roomType->capacity
            * (int) $data['number_of_rooms'];

        if ($totalGuests > $maximumGuests) {
            throw ValidationException::withMessages([
                'adults' =>
                    "The selected rooms can accommodate a maximum of {$maximumGuests} guests.",
            ]);
        }
    }
}