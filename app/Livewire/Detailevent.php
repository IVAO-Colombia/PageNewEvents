<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Route;

class Detailevent extends Component
{
    public $event;
    public $stats = [];
    public $countStart;
    public $mapStyle;

    public $trasanslation;

    public function mount($eventId)
    {
        $this->event = Event::where('name', $eventId)->firstOrFail();

        $eventId = $this->event->id;

        $startEvent = $this->event->start_time;

        $this->stats = $this->getEventStats($eventId);

        $locale = app()->getLocale();
        if ($locale == 'es') {
            $this->trasanslation = $this->event->description_es;
        } else {
            $this->trasanslation = $this->event->description;
        }

        $this->mapStyle = config('services.maptiler.style');
    }

    private function getEventStats($eventId)
    {
        $countFlights = Route::where('event_id', $eventId)->count();
        $countFlightsAvailable = Route::where('event_id', $eventId)
            ->where('is_active', 1)
            ->count();
        $countFlightsNotAvailable = Route::where('event_id', $eventId)
            ->where('is_active', 0)
            ->count();

        return [
            'total' => $countFlights,
            'available' => $countFlightsAvailable,
            'not_available' => $countFlightsNotAvailable,
        ];
    }


    public function render()
    {
        return view('livewire.detailevent', ['event' => $this->event, 'stats' => $this->stats, 'trasanslation' => $this->trasanslation, 'mapStyle' => $this->mapStyle]);
    }
}
