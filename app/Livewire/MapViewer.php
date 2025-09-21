<?php

namespace App\Livewire;

use Livewire\Component;

class MapViewer extends Component
{

    public string $apiKey = 'JrVOH4Ymkw6FIR8FsCKo';
    public string $mapId = '01996dc8-dc01-7ea0-ab8b-cad2a660569d';


    public string $mapStyle = 'https://api.maptiler.com/maps/01996dc8-dc01-7ea0-ab8b-cad2a660569d/style.json?key=JrVOH4Ymkw6FIR8FsCKo';

    public float $initialLat = 4.6097;
    public float $initialLng = -74.0817;
    public int $initialZoom = 5;


    public function render()
    {
        return view('livewire.map-viewer');
    }
}
