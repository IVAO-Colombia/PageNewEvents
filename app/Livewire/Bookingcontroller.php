<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Services\Ivao;
use App\Models\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Bookingcontroller extends Component
{
    public $bookingId;
    public $reservedSlots = [];

    public function mount($bookingId)
    {
        $this->bookingId = Event::where('name', $bookingId)->first();

        $this->loadReservedSlots();
    }


    private function loadReservedSlots()
    {
        $controllers = Controller::where('event_id', $this->bookingId->id)->get();

        foreach ($controllers as $controller) {
            $position = $controller->position;

            $startTime = Carbon::parse($controller->start);


            $key = $position . '_' . $startTime->format('Y-m-d_H');
            $this->reservedSlots[$key] = [
                'vid' => $controller->vid,
                'isCurrentUser' => ($controller->vid == Auth::user()->vid_ivao)
            ];
        }
    }


    public function isSlotReserved($position, $dateTime)
    {
        $key = $position . '_' . $dateTime->format('Y-m-d_H');
        return isset($this->reservedSlots[$key]);
    }


    public function isSlotReservedByCurrentUser($position, $dateTime)
    {
        $key = $position . '_' . $dateTime->format('Y-m-d_H');
        return isset($this->reservedSlots[$key]) && $this->reservedSlots[$key]['isCurrentUser'];
    }

    public function bookSlot($vid, $position, $date_start)
    {
        $ivao = new Ivao();
        $date_end = Carbon::parse($date_start)->addHour()->format('Y-m-d H:i:s');
        $formattedTime = Carbon::parse($date_start)->format('d/m/Y H:i');


        $slotDateTime = Carbon::parse($date_start);
        if ($this->isSlotReserved($position, $slotDateTime)) {
            $this->dispatch('slotError', 'Esta posición ya está reservada para este horario');
            return;
        }

        $statusrol = $ivao->verifiedRankAtc($position, $vid);
        if($statusrol === 200) {
            Controller::create([
                'event_id' => $this->bookingId->id,
                'vid' => $vid,
                'position' => $position,
                'start' => $date_start,
                'end' => $date_end,
            ]);

            $key = $position . '_' . $slotDateTime->format('Y-m-d_H');
            $this->reservedSlots[$key] = [
                'vid' => $vid,
                'isCurrentUser' => true
            ];

            $this->dispatch('slotBooked', $position, $formattedTime);
        } else {
            $this->dispatch('slotError', __('Your ATC rating does not meet the event requirements.'));
        }
    }

    public function cancelBooking($position, $date_start)
    {
        $slotDateTime = Carbon::parse($date_start);
        $formattedTime = $slotDateTime->format('d/m/Y H:i');
        $key = $position . '_' . $slotDateTime->format('Y-m-d_H');

        if (!isset($this->reservedSlots[$key]) || !$this->reservedSlots[$key]['isCurrentUser']) {
            $this->dispatch('slotError', 'No puedes cancelar esta reserva');
            return;
        }

        Controller::where('event_id', $this->bookingId->id)
            ->where('position', $position)
            ->where('start', $date_start)
            ->where('vid', Auth::user()->vid_ivao)
            ->delete();


        $deleted = Controller::where('event_id', $this->bookingId->id)
            ->where('position', $position)
            ->where('vid', Auth::user()->vid_ivao)
            ->delete();

        if ($deleted) {
            unset($this->reservedSlots[$key]);
            $this->dispatch('slotCancelled', $position, $formattedTime);
        } else {
            $this->dispatch('slotError', 'Error al cancelar la reserva. Por favor, inténtalo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.bookingcontroller', [
            'bookingId' => $this->bookingId
        ]);
    }
};
