<?php

namespace App\View\Components;

use App\Models\Hotel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HotelCard extends Component
{
    public Hotel $hotel;

    public string $status;

    /**
     * Create a new component instance.
     */
    public function __construct(Hotel $hotel, string $status = 'customer')
    {
        $this->hotel = $hotel;
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.hotel-card');
    }
}