<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class Newsevents extends Component
{
    public $event;

    public function mount()
    {
        $this->eventsNow();
    }

    private function eventsNow()
    {
        $this->event = Event::where('start_time', '>=', now())
            ->where('is_active', '1')
            ->first();
    }
    public function render()
    {
        return view('livewire.newsevents', [
            'event' => $this->event
        ]);
    }
}
