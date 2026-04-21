<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class Allreservations extends Component
{

    public $bookings;
    public $user;


    public function mount()
    {
        $this->user = Auth::user();

        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $this->bookings = Booking::where('VID', $this->user->vid_ivao)->get();
    }


    public function CanceledReservation($routeId)
    {
        Booking::where('route_id', $routeId)
            ->where('VID', $this->user->vid_ivao)
            ->delete();

        Route::where('id', $routeId)
            ->update(['is_active' => true]);

        $this->mount();
    }


    public function render()
    {
        return view('livewire.allreservations', [
            'bookings' => $this->bookings
        ]);
    }
}
