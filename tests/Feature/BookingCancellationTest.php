<?php

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\RoomTypes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('allows a customer to cancel a pending booking', function () {
    $user = User::factory()->create();
    $hotelOwner = User::factory()->create();
    $cityId = DB::table('cities')->insertGetId([
        'name' => 'Kathmandu',
        'country' => 'Nepal',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $hotel = Hotel::query()->create([
        'owner_id' => $hotelOwner->id,
        'name' => 'Test Hotel',
        'slug' => 'test-hotel',
        'description' => 'A test hotel.',
        'address' => 'Test Street',
        'city_id' => $cityId,
        'district' => 'Kathmandu',
        'country' => 'Nepal',
        'star_rating' => 4,
        'phone' => '9800000000',
        'email' => 'hotel@example.com',
        'checkin_time' => '13:00:00',
        'check_out_time' => '12:00:00',
        'featured_image' => 'hotel.jpg',
        'is_featured' => false,
        'status' => 'approved',
    ]);

    $roomType = RoomTypes::query()->create([
        'hotel_id' => $hotel->id,
        'name' => 'Standard Room',
        'description' => 'A standard room.',
        'capacity' => 2,
        'bed_type' => 'Double',
        'room_size' => 320,
        'price' => 2000.00,
        'discount_price' => 1800.00,
        'total_rooms' => 2,
        'available_rooms' => 2,
    ]);

    $booking = Booking::query()->create([
        'booking_number' => 'BK-TEST1234',
        'user_id' => $user->id,
        'hotel_id' => $hotel->id,
        'room_type_id' => $roomType->id,
        'check_in' => now()->addDays(2)->toDateString(),
        'check_out' => now()->addDays(4)->toDateString(),
        'adults' => 2,
        'children' => 0,
        'number_of_rooms' => 1,
        'total_price' => 2500.00,
        'booking_status' => 'pending',
        'payment_status' => 'pending',
        'phone_number' => '9800000000',
        'expires_at' => now()->addMinutes(15),
    ]);

    $this->actingAs($user);

    $response = app(\App\Http\Controllers\BookingController::class)->destroy($booking);

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class)
        ->and($booking->fresh()->booking_status)->toBe('cancelled');
});
