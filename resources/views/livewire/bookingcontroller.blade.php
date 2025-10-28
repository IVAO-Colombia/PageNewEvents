<div class="w-full overflow-x-auto">
        <script>
            window.translations = {
                successBooking: "{{ __('You have successfully reserved the position') }}",
                bookingCancelled: "{{ __('Booking cancelled:') }}",
                processing: "{{ __('Processing...') }}",
                book: "{{ __('Book') }}",
                cancel: "{{ __('Cancel') }}",
                occupied: "{{ __('Occupied') }}"
            };
        </script>
    <table class="min-w-full text-xs md:text-sm">
        <!-- Encabezados de horas -->
        <thead>
            <tr class="text-center border-b dark:border-gray-700">
                <th class="py-3 px-2 bg-gray-800 text-white">{{ __('Position') }}</th>
                @php
                    $startTime = \Carbon\Carbon::parse($this->bookingId->start_time);
                    $endTime = \Carbon\Carbon::parse($this->bookingId->end_time);

                    $startHour = $startTime->hour;

                    $endHour = $endTime->minute > 0 ? $endTime->hour + 1 : $endTime->hour;
                    $endHour = min($endHour, 23); 
                @endphp

                @for ($hour = $startHour; $hour <= $endHour; $hour++)
                    <th class="py-3 px-2 bg-gray-800 text-white">
                        {{ sprintf('%02d:00', $hour) }}
                    </th>
                @endfor
            </tr>
        </thead>

        <!-- Contenido de la tabla -->
        <tbody>
            @php
                $searchPositions = app(\App\Services\Ivao::class)->getPositionsAtc($this->bookingId->icao);
                $positions = [];
                foreach ($searchPositions as $pos) {
                    if (is_array($pos)) {
                        $positions[$pos['composePosition']] = 'green';
                    } elseif (is_object($pos)) {
                        $positions[$pos->composePosition] = 'green';
                    }
                }
            @endphp

            @foreach ($positions as $position => $color)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2 px-2 bg-gray-700 text-white font-medium">{{ $position }}</td>

                    @for ($hour = $startHour; $hour <= $endHour; $hour++)
                        @php
                            $slotDateTime = $startTime->copy()->setTime($hour, 0, 0);
                            $isAvailable = !$this->isSlotReserved($position, $slotDateTime);
                            $isMyBooking = $this->isSlotReservedByCurrentUser($position, $slotDateTime);
                        @endphp

                        <td class="py-2 px-1">
                            @if($isAvailable)
                                <button
                                    type="button"
                                    class="w-full {{ $color == 'red' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 px-1 rounded text-xs transition-colors relative cursor-pointer"
                                    wire:click="bookSlot('{{ Auth::user()->vid_ivao }}', '{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="bookSlot('{{ Auth::user()->vid_ivao }}', '{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')"
                                >
                                    <span wire:loading.remove wire:target="bookSlot('{{ Auth::user()->vid_ivao }}', '{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')">
                                        {{ __('Book') }}
                                    </span>
                                    <span wire:loading wire:target="bookSlot('{{ Auth::user()->vid_ivao }}', '{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Processing...') }}
                                    </span>
                                </button>
                            @elseif($isMyBooking)
                                <button
                                    type="button"
                                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-1 rounded text-xs transition-colors relative cursor-pointer"
                                    wire:click="cancelBooking('{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="cancelBooking('{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')"
                                >
                                    <span wire:loading.remove wire:target="cancelBooking('{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')">
                                        {{ __('Cancel') }}
                                    </span>
                                    <span wire:loading wire:target="cancelBooking('{{ $position }}', '{{ $slotDateTime->format('Y-m-d H:i:s') }}')" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Processing...') }}
                                    </span>
                                </button>
                            @else
                                <button
                                    type="button"
                                    class="w-full bg-gray-400 cursor-not-allowed text-white py-2 px-1 rounded text-xs"
                                    disabled
                                >
                                    {{ __('Occupied') }}
                                </button>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('slotBooked', function(position, time) {
            const message = `${window.translations.successBooking} ${position || ''} ${time || ''}`;
            Toastify({
                text: message,
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
                stopOnFocus: true
            }).showToast();
        });

        Livewire.on('slotCancelled', function(position, time) {
            const message = `${window.translations.bookingCancelled} ${position || ''} ${time || ''}`;
            Toastify({
                text: message,
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #3f4c6b, #606c88)",
                },
                stopOnFocus: true
            }).showToast();
        });

        Livewire.on('slotError', function(errorMessage) {
            Toastify({
                text: errorMessage,
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#e74c3c",
                stopOnFocus: true
            }).showToast();
        });
    });
</script>
