<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class Newsevents extends Component
{
    public $events;

    public function mount()
    {
        $this->eventsNow();
    }

    private function eventsNow()
    {
        $this->events = Event::where(function($query) {
                $query->whereDate('start_time', '<=', now())
                    ->whereDate('end_time', '>=', now());
            })
            ->orWhere(function($query) {
                $query->whereDate('start_time', '>', now())
                    ->whereDate('start_time', '<=', now()->addDays(7));
            })
            ->orderBy('start_time', 'asc')
            ->get();
    }
    public function render()
    {
        return view('livewire.newsevents', [
            'events' => $this->events
        ]);
    }
}
