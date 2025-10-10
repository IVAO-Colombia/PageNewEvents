<div class="relative h-full w-full">
    <!-- Botón del menú -->
    <button id="toggle-menu" class="absolute top-4 left-4 z-10 bg-white p-2 rounded-md shadow-lg hover:bg-gray-100 cursor-pointer" onclick="toggleMenu()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Menú lateral -->
    <div id="events-menu" class="absolute top-0 left-0 z-20 bg-white w-80 h-full shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto ">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 poppins-bold">{{ __('EventsActive') }}</h2>
            <button id="close-menu" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 cursor-pointer" onclick="toggleMenu()" style="display: none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="events-list" class="p-4 space-y-3">
        </div>
    </div>

    <!-- Contenedor del mapa -->
    <div wire:ignore id="map-container" class="h-full w-full rounded-xl overflow-hidden"></div>

    @push('scripts')
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.umd.min.js"></script>

    <script>

        function toggleMenu() {
            const menu = document.getElementById('events-menu');
            const closemenu = document.getElementById('close-menu');
            menu.classList.toggle('-translate-x-full');

            if(menu.classList.contains('-translate-x-full')) {
                closemenu.style.display = 'none';
            } else {
                closemenu.style.display = 'block';
            }
        }


        document.addEventListener('livewire:initialized', function () {
            const apiKey = @js($apiKey);
            const mapId = @js($mapId);


            maptilersdk.config.apiKey = apiKey;

            // Inicializar el mapa
            const map = new maptilersdk.Map({
                container: 'map-container',
                style: @js($mapStyle),
                center: [@js($initialLng), @js($initialLat)],
                zoom: @js($initialZoom),
                projection: 'globe'
            });


            const events = @js($eventsData);

            // Inicializar menú de eventos
            function initializeEventMenu() {
                const eventsList = document.getElementById('events-list');

                events.forEach(event => {
                    // Crear elemento para cada evento


                    const eventElement = document.createElement('div');
                    eventElement.className = 'p-3 bg-gray-50 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors';
                    eventElement.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-800">${event.title}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2 overflow-hidden text-ellipsis">${event.description}</p>
                        <p class="text-xs text-gray-500 mt-1">${event.date}</p>
                    `;

                    // Añadir evento de clic para centrar el mapa
                    eventElement.addEventListener('click', () => {
                        // Fly to event location
                        map.flyTo({
                            center: event.coordinates,
                            zoom: 10,
                            duration: 2000
                        });

                        // Mostrar popup después de centrar
                        setTimeout(() => {
                            showEventPopup(event);
                        }, 2000);

                        // Cerrar menú en dispositivos móviles
                        if (window.innerWidth < 768) {
                            toggleMenu();
                        }
                    });

                    eventsList.appendChild(eventElement);
                });
            }

            // Mostrar popup de evento
            function showEventPopup(event) {
                // Si ya existe un popup, eliminarlo
                const existingPopup = document.querySelector('.mapboxgl-popup');
                if (existingPopup) {
                    existingPopup.remove();
                }

                // Crear nuevo popup
                const popup = new maptilersdk.Popup({ closeOnClick: false })
                    .setLngLat(event.coordinates)
                    .setHTML(`
                        <div class="p-2">
                            <h3 class="text-lg poppins-semibold">${event.title}</h3>
                            <p class="text-sm poppins-regular">${event.description}</p>
                            <p class="text-xs text-gray-600 poppins-regular">${event.date}</p>
                            <p class="text-xs text-gray-600 poppins-regular">${event.location}</p>
                            <button class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors poppins-semibold cursor-pointer"
                                onclick="window.showEventDetails(${event.id})">
                                Más información
                            </button>
                        </div>
                    `)
                    .addTo(map);
            }

            // Función global para mostrar detalles del evento
            window.showEventDetails = function(eventId) {
                const event = events.find(e => e.id === eventId);
                if (!event) return;
                const eventUrl = `/events/${event.title}`;
                window.location.href = eventUrl;
            }


            // Cuando el mapa se carga, añadir marcadores
            map.on('load', function() {
                console.log('Mapa cargado correctamente');

                // Añadir marcadores para cada evento
                events.forEach(event => {
                    // Crear un elemento DOM para el marcador
                    const el = document.createElement('div');
                    el.className = 'marker';
                    el.style.width = '30px';
                    el.style.height = '30px';
                    el.style.backgroundImage = 'url(https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-512.png)';
                    el.style.backgroundSize = '100%';
                    el.style.cursor = 'pointer';

                    // Añadir marcador al mapa
                    new maptilersdk.Marker(el)
                        .setLngLat(event.coordinates)
                        .addTo(map);

                    // Añadir evento de clic al marcador
                    el.addEventListener('click', () => {
                        showEventPopup(event);
                    });
                });

                // Inicializar el menú de eventos
                initializeEventMenu();
            });
        });
    </script>

    <style>
        /* Estilos para el menú y marcadores */
        .marker {
            transition: transform 0.2s;
        }
        .marker:hover {
            transform: scale(1.2);
        }
        .mapboxgl-popup-content {
            padding: 10px;
            border-radius: 8px;
        }
    </style>
    @endpush
</div>
