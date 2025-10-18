<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class Eventcontroller extends Component
{
    public $events;

    public function mount()
    {
        $this->events = Event::all();
    }
    
    public function render()
    {
        return view('livewire.eventcontroller', [
            'events' => $this->events
        ]);
    }
}
