<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Hotel;
use App\Models\HotelImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelImageController extends Controller
{
    
    public function store(Request $request , Hotel $hotel){

        //check whether or not the user is hotel owner or not
        Gate::authorize('create', $hotel);

        // Image validation
        $request->validate([
            'image'=>['required', 'image', 'max:5120'],
        ]);

        $path = $request->file('image')->store('hotel-images', 'public');

        // Hotel images banayera $path ma save garna lai
        $hotel->image()->create([
            'image' => $path,
        ]);

        // User lai with error previous page ma lagna ko lagi
        return back()->with([
            'success' => 'Hotel Image uploaded Successfully',
            'image_path' => $path,
        ]);

    }

    public function update(HotelImages $image){
        
    }
    
    public function destroy(HotelImages $image){
        // Check whether or not the user is hotel owner or not\
        Gate::authorize('delete', $image);
        Storage::disk("public")->delete($image->image);
        $image->delete();
        return back()->with('success', 'image deleted successfully');
    }
}
