<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Cities;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display featured hotels on the homepage.
     */
    public function featured()
    {
        $featuredHotels = Hotel::with(['roomTypes', 'amenity'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->get();

        return view('index', [
            'featuredHotels' => $featuredHotels,
        ]);
    }


    /**
     * Display hotels available for customers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', $request->input('location'));
        $cityId = $request->integer('city_id') ?: null;
        $minRating = $request->input('min_rating');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'recommended');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $guests = $request->input('guests');

        $hotels = Hotel::with([
            'roomTypes',
            'amenity',
            'city',
        ])
            ->where('status', 'active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('city', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($cityId, fn ($query) => $query->where('city_id', $cityId))
            ->when($minRating, fn ($query) => $query->where('star_rating', '>=', $minRating))
            ->when($maxPrice, fn ($query) => $query->whereHas('roomTypes', function ($query) use ($maxPrice) {
                $query->whereRaw('COALESCE(discount_price, price) <= ?', [$maxPrice]);
            }))
            ->when($sort === 'rating', fn ($query) => $query->orderByDesc('star_rating'))
            ->when($sort === 'name', fn ($query) => $query->orderBy('name'))
            ->get();

        if ($sort === 'price_low' || $sort === 'price_high') {
            $hotels = $hotels->sortBy(function ($hotel) {
                return $hotel->roomTypes->min(fn ($room) => $room->discount_price ?? $room->price) ?? PHP_INT_MAX;
            }, SORT_NUMERIC, $sort === 'price_high')->values();
        }

        $cities = Cities::query()->orderBy('name')->get(['id', 'name']);
        $priceMaximum = (int) (\App\Models\RoomTypes::query()->selectRaw('MAX(COALESCE(discount_price, price)) as maximum')->value('maximum') ?? 0);

        return view('hotels.hotelList', [
            'hotels' => $hotels,
            'search' => $search,
            'cityId' => $cityId,
            'minRating' => $minRating,
            'maxPrice' => $maxPrice,
            'sort' => $sort,
            'cities' => $cities,
            'priceMaximum' => $priceMaximum,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
        ]);
    }


    /**
     * Display a hotel.
     */
    public function show(Hotel $hotel)
    {
        // Customers should only be able to view active hotels.
        abort_if($hotel->status !== 'active', 404);

        $hotel->load([
            'image',
            'roomTypes',
            'amenity',
        ]);

        return view('hotels.customerHotelDetails', [
            'hotel' => $hotel,
        ]);
    }
}
