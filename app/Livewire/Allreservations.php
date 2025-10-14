<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class Allreservations extends Component
{

    public $reserves;


    public function mount()
    {
        $this->reserves = $this->getinformationReserved();
    }

    private function getinformationReserved()
    {
        $user = Auth::user();
        $iduser = Booking::where('VID', $user->vid_ivao)->first();


        if (!$iduser) {
            return null;
        }


        $idevent = Event::where('id', $iduser->Name_event)->first();
        $idroute = Route::where('id', $iduser->route_id)->first();

        $result = [
            'data' => [
                'routeid'   => $iduser->route_id,
                'nameevent' => $idevent->name,
                'dateevent' => $idevent->start_time,
                'departure' => $idroute->origin,
                'arrival'   => $idroute->destination,
                'callsing'  => $iduser->Callsign,
            ]
        ];

        return $result;
    }


    public function CanceledReservation($routeid)
    {
        $user = Auth::user();
        Booking::where('route_id', $routeid)
            ->where('VID', $user->vid_ivao)
            ->delete();

        Route::where('id', $routeid)
        ->update(['is_active' => true]);

        $this->mount();
    }


    public function render()
    {
        return view('livewire.allreservations',[
            'data' => $this->reserves
        ]);
    }
}
