<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Route;
Use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class Bookingdetail extends Component
{
    public $information;
    public $dateEvent;
    public $mapStyle;

    public $isActive;

    public $email;
    public $name;
    public $callsign;
    public $departure;
    public $arrival;
    public $vid;


    public function mount($bookingId)
    {
        $this->information = $this->getInformation($bookingId);
        $this->dateEvent = $this->getDateEvent($bookingId);

        $this->mapStyle = config('services.maptiler.style');

        // Pre Information to user
        $this->email = Auth::user()->email;
        $this->name = User::find(Auth::id())->fullName();
        $this->vid = Auth::user()->vid_ivao;
        $this->callsign = $this->information->flight_number ?? '00000';

        $this->departure = $this->information->origin;
        $this->arrival = $this->information->destination;

        $this->isActive = $this->isactiveRoute($this->information->id);

    }

    private function getInformation($bookingId)
    {
        return Route::where('id', $bookingId)->first();
    }

    private function getDateEvent($bookingId)
    {
        $route = Route::find($bookingId);
        if ($route) {
            $event = Event::find($route->event_id);
            return $event ? $event->start_time : null;
        }
        return null;

    }

    public function saveBooking()
    {
        $this->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'callsign' => 'required|string|max:10',
            'departure' => 'required|string|max:10',
            'arrival' => 'required|string|max:10',
            'vid' => 'required|integer|min:1|max:9999999',
        ],
        [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'name.required' => 'The name field is required.',
            'callsign.required' => 'The callsign field is required.',
            'departure.required' => 'The departure field is required.',
            'arrival.required' => 'The arrival field is required.',
            'vid.required' => 'The VID field is required.',
            'vid.integer' => 'The VID must be an integer.',
            'vid.min' => 'The VID must be at least 1.',
            'vid.max' => 'The VID may not be greater than 9999999.',
        ]);

        $formatedCallsign = $this->information->airline . $this->callsign;

        Booking::create([
            'Name_event' => $this->information->event_id,
            'route_id' => $this->information->id,
            'Callsign' => $formatedCallsign,
            'Departure' => $this->information->origin,
            'Arrival' => $this->information->destination,
            'VID' => $this->vid,
        ]);

        Route::where('id', $this->information->id)->update(['is_active' => false]);



        session()->flash('message', 'Booking saved successfully!');


        $this->dispatch('booking-saved', [
            'redirect' => route('dashboard')
        ]);

    }

    private function isactiveRoute($routeId)
    {
        $route = Route::find($routeId);
        return $route ? $route->is_active : false;
    }

    public function render()
    {
        return view('livewire.bookingdetail', [
            'information' => $this->information,
            'dateEvent' => $this->dateEvent,
            'isActive' => $this->isActive,
        ]);
    }
}
