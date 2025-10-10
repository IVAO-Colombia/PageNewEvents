<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Str;


class MapViewer extends Component
{

    public array $eventsData = [];
    public $events;

    public string $apiKey;
    public string $mapId;
    public string $mapStyle;
    public float $initialLat;
    public float $initialLng;
    public int $initialZoom;

    public string $trasanslation = '';


    public function mount()
    {
        $this->apiKey = config('services.maptiler.key', 'default_key_here');
        $this->mapId = config('services.maptiler.map_id', 'default_map_id');
        $this->mapStyle = config('services.maptiler.style', 'default_style_url');
        $this->initialLat = (float) config('services.maptiler.initial_latitude', 4.6097);
        $this->initialLng = (float) config('services.maptiler.initial_longitude', -74.0817);
        $this->initialZoom = (int) config('services.maptiler.initial_zoom', 5);


        $this->events = $this->getEvents();


        $this->mapStyle = config('services.maptiler.style');

        $this->eventsData = $this->events->map(function (Event $e) {
            $date = null;
            if ($e->start_time instanceof \Carbon\Carbon) {
                $date = $e->start_time->format('d M Y');
            } elseif (! empty($e->start_time)) {
                $date = (string) $e->start_time;
            }

            $locale = app()->getLocale();

            $description = $locale == 'es' ? ($e->description_es ?? $e->description) : $e->description;

            return [
                'id' => $e->id,
                'title' => $e->title ?? $e->name ?? '',
                'description' => Str::limit($description, 250) ?? '',
                'date' => $date,
                'location' => $e->name_airport ?? $e->location ?? '',
                'coordinates' => [
                    (float) ($e->longitude ?? $e->longitude_deg ?? 0),
                    (float) ($e->latitude  ?? $e->latitude_deg  ?? 0),
                ],
            ];
        })->toArray();


    }


    private function getEvents()
    {
        return Event::all();
    }

    public function render()
    {
        return view('livewire.map-viewer');
    }
}
