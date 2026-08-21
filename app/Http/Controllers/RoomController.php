<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

class RoomController extends Controller
{
    // Hotel vitra ko rooms dekhauna ko lagi
    public function index(Hotel $hotel) {
        $rooms = $hotel->rooms;
        return view('rooms.index', [
            'hotel'=>$hotel,
            'rooms'=>$rooms,
        ]);
    }

    // Room create garne page dekhauna
    public function create(){
        return view('rooms.create');
    }

    // Create gareko room db ma store garna
    public function store(){

    }

    public function edit(){
        return view('rooms.edit');
    }

    // edit gareko room lai db ma update garna
    public function update(){

    }

    // rooms haru delete garna
    public function destroy(){

    }
}
