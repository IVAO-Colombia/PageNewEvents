<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Fligths;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Models\Airlines;

class BookingPage extends Component
{
    public $user;
    public $fligth;

    public $name;
    public $email;
    public $ivaoid;

    public function mount($id_fligth)
    {
        $this->user = Auth::user();
        $this->fligth = $this->getFligth($id_fligth);
        $this->email = Auth::user()->email;
        $this->name = Auth::user()->name;
        $this->ivaoid = Auth::user()->ivao_id;

    }

    public function getFligth($id_fligth)
    {
        $fligth = Fligths::find($id_fligth);

        if (!$fligth) {
            abort(404);
        }


        return $fligth;
        
    }

    public function submitBooking()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'ivaoid' => 'required',
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'ivaoid.required' => 'El IVAO ID es obligatorio.',
        ]);

        DB::beginTransaction();
        try {
            Booking::create([
                'user_id' => $this->user->id,
                'flight_id' => $this->fligth->id, // CORREGIDO
                'user_name' => $this->name,
                'user_email' => $this->email,
                'ivao_id' => $this->ivaoid,       // CORREGIDO
                'user_pilot_rating' => $this->user->pilot_rating_name,
            ]);

            Fligths::where('id', $this->fligth->id)
                ->update(['is_cancelled' => true]);
            
        
            DB::commit();
            $this->dispatch('session-success');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('session-error');
            Log::error('Booking failed: ' . $e->getMessage());

            
        }
        

    }

    public function searchLogo($icao_fligth)
    {

        $airline = Airlines::where('icao', $icao_fligth)->first();
        return $airline ? $airline->url : null;
    }

    public function render()
    {
        return view('livewire.pages.booking',
            [
                'user' => $this->user,
                'fligth' => $this->fligth,
                'airline_logo' => $this->searchLogo($this->fligth->IcaoAirline),  
            ]
        )
        ->layout('layouts.app');
    }
}
