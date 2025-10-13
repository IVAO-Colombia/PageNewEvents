<div>
    <section class="bg-white">
         <div class="container mx-auto flex justify-between items-center p-4 text-center mb-4 py-6">
            <div class="flex flex-col items-center text-center gap-2">
                <h2 class="text-2xl font-medium">{{ __('Origin') }}</h2>
                <h1 class="text-8xl font-bold text-black">{{ $information->iato_code_origin }}</h1>
                <h3 class="text-xl font-medium">{{ $information->name_airport_origin }}</h3>
            </div>
                <div class="flex-1 flex items-center justify-center px-4 relative mx-4">
                    <div class="border-t-2 border-gray-300 w-full absolute"></div>
                    <div class="bg-white dark:bg-gray-800 px-3 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 dark:text-blue-400 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                </div>
            <div class="flex flex-col items-center text-center gap-2">
                <h2 class="text-2xl font-medium">{{ __('Destination') }}</h2>
                <h1 class="text-8xl font-bold text-amber-500">{{ $information->iato_code_destination }}</h1>
                <h3 class="text-xl font-medium">{{ $information->name_airport_destination }}</h3>
            </div>
         </div>

        <div class="container mx-auto mt-6 mb-8">
            <div class="grid grid-cols-7 gap-4 text-center">
                <!-- Encabezados -->
                <div class="text-gray-600 text-sm">{{ __('Date') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Estimated Time Departure') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Estimated Time Arrival') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Flight') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Airline') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Gate Origin') }}</div>
                <div class="text-gray-600 text-sm">{{ __('Gate Destination') }}</div>

                <!-- Valores -->
                <div class="font-medium">{{ \Carbon\Carbon::parse($dateEvent)->format('d-m-y') }}</div>
                <div class="font-medium">{{ $information->hourOrigin }}</div>
                <div class="font-medium">{{ $information->hourDestination }}</div>
                <div class="font-medium">{{ $information->airline }}{{ $information->flight_number }}</div>
                <div>
                    @php
                        $airlineData = app(\App\Services\Ivao::class)->getImagenAirlines($information->airline);
                        $logoUrl = $airlineData['logo'];
                    @endphp
                    <img class="items-center px-3 mx-auto" src="{{ $logoUrl }}" alt="{{ $information->airline }}">
                </div>
                <div>
                    <div class="bg-[#f08c00] text-white font-bold rounded w-10 h-8 flex items-center justify-center mx-auto">
                        {{ $information->gate_origin ?? 'X'  }}
                    </div>
                </div>
                <div>
                    <div class="bg-[#37fc1d] text-white font-bold rounded w-10 h-8 flex items-center justify-center mx-auto">
                        {{ $information->gate_destination ?? 'X' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto mt-6 mb-8">
            <div wire:ignore id="map" class="w-full h-64 md:h-96 rounded-lg overflow-hidden shadow-lg"></div>
        </div>

        <div class="container mx-auto mt-10 mb-12">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                @if($isActive === false)
                    <div class="bg-red-500 p-6">
                        <h2 class="text-2xl font-bold text-white">{{ __('Booking Closed') }}</h2>
                        <p class="text-red-100 mt-1">{{ __('Sorry, bookings for this flight are now closed.') }}</p>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <h2 class="text-2xl font-bold text-white">{{ __('Confirm your booking') }}</h2>
                        <p class="text-blue-100 mt-1">{{ __('Enter your details to complete your flight booking') }}</p>
                    </div>

                    <form wire:submit.prevent="saveBooking" class="p-6 space-y-6">
                        <!-- Sección principal de datos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Email') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="email"
                                        id="email"
                                        wire:model="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 pl-10 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-all duration-200"
                                        required
                                    >
                                </div>
                                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- VID (IVAO ID) -->
                            <div>
                                <label for="vid" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('VID (IVAO ID)') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        id="vid"
                                        wire:model="vid"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 pl-10 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-all duration-200"
                                        placeholder="123456"
                                        pattern="\d{6}"
                                        title="Por favor ingresa un VID válido de 6 dígitos"
                                        required
                                    >
                                </div>
                                @error('vid') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Nombre completo -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Nombre completo') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    wire:model="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="John Doe"
                                    required
                                >
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Callsign (prefijado con la aerolínea) -->
                            <div>
                                <label for="callsign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Callsign') }}
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-white bg-blue-600 border border-r-0 border-blue-600 rounded-l-lg">
                                        {{ $information->airline }}
                                    </span>
                                    <input
                                        type="text"
                                        id="callsign"
                                        wire:model="callsign"
                                        class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="1234"
                                        readonly
                                    >
                                </div>
                                @error('callsign') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <input type="hidden" wire:model="departure">
                        <input type="hidden" wire:model="arrival">

                        <!-- Botón de envío -->
                        <div class="relative flex justify-end mt-8">
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="saveBooking"
                                class="inline-flex items-center gap-2 w-auto px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm hover:bg-blue-700 transition cursor-pointer"
                            >
                                <span class="flex items-center gap-2">
                                    {{ __('Confirm your booking') }}
                                    <svg class="h-4 w-4" ...>...</svg>
                                </span>
                            </button>
                            <div
                                wire:loading
                                wire:target="saveBooking"
                                class="absolute inset-0 bg-white/75 flex items-center justify-center rounded-lg"
                            >
                                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </div>
                    </form>
                @endif
            </div>
                @if (session()->has('message'))
                    <div id="success-message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('message') }}</p>
                    </div>
                @endif
        </div>
    </section>
</div>
<script>
    const map = new maptilersdk.Map({
        container: 'map',
        center: [{{ $information->longitude_origin }}, {{ $information->latitude_origin }}], // starting position [lng, lat]
        style: @json($mapStyle),
        zoom: 10,
    });

    // Add markers for origin and destination
    const originMarker = new maptilersdk.Marker({ color: 'blue' })
        .setLngLat([{{ $information->longitude_origin }}, {{ $information->latitude_origin }}])
        .setPopup(new maptilersdk.Popup().setText('{{ $information->name_airport_origin }} ({{ $information->iato_code_origin }})'))
        .addTo(map);
    const destinationMarker = new maptilersdk.Marker({ color: 'red' })
        .setLngLat([{{ $information->longitude_destination }}, {{ $information->latitude_destination }}])
        .setPopup(new maptilersdk.Popup().setText('{{ $information->name_airport_destination }} ({{ $information->iato_code_destination }})'))
        .addTo(map);


    map.on('load', function () {

        map.addSource('route', {
            'type': 'geojson',
            'data': {
                'type': 'Feature',
                'properties': {},
                'geometry': {
                    'type': 'LineString',
                    'coordinates': [
                        [{{ $information->longitude_origin }}, {{ $information->latitude_origin }}],
                        [{{ $information->longitude_destination }}, {{ $information->latitude_destination }}]
                    ]
                }
            }
        });


        map.addLayer({
            'id': 'route-line',
            'type': 'line',
            'source': 'route',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#3b82f6',
                'line-width': 3,
                'line-dasharray': [2, 2]
            }
        });


        const bounds = new maptilersdk.LngLatBounds()
            .extend([{{ $information->longitude_origin }}, {{ $information->latitude_origin }}])
            .extend([{{ $information->longitude_destination }}, {{ $information->latitude_destination }}]);

        map.fitBounds(bounds, { padding: 80 });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    window.addEventListener('booking-saved', function(e) {
        var count = 200;
        var defaults = { origin: { y: 0.7 } };
        function fire(particleRatio, opts) {
            confetti(Object.assign({}, defaults, opts, {
                particleCount: Math.floor(count * particleRatio)
            }));
        }
        fire(0.25, { spread: 26, startVelocity: 55 });
        fire(0.2,  { spread: 60 });
        fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
        fire(0.1,  { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
        fire(0.1,  { spread: 120, startVelocity: 45 });

        // redirigir tras 5s
        setTimeout(function() {
            if (e.detail && e.detail.redirect) {
                window.location.href = e.detail.redirect;
            } else {
                window.location.href = '{{ route("dashboard") }}';
            }
        }, 2000);
    });
</script>
