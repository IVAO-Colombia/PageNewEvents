<div>
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header con búsqueda -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Flight Routes') }}</h1>

                <!-- Barra de búsqueda -->
                <div class="w-full md:max-w-xs">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input
                            wire:model.live.debounce.300ms="searchTerm"
                            wire:keydown.enter="search"
                            type="search"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-600 dark:focus:border-blue-500"
                            placeholder="{{ __('Search flight, airport...') }}"
                        >
                    </div>
                </div>
            </div>

            <!-- Filtros primarios (responden en móvil como stack) -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Filter by') }}</h2>
                <div class="flex flex-wrap gap-2">
                    <button
                        wire:click="filterByType('all')"
                        type="button"
                        class="text-white {{ $typeFilter === 'all' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-700 hover:bg-blue-800' }} focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 cursor-pointer">
                        {{ __('All Types') }}
                    </button>
                    <button
                        wire:click="filterByType('departure')"
                        type="button"
                        class="text-white {{ $typeFilter === 'departure' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-700 hover:bg-blue-800' }} focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 cursor-pointer">
                        {{ __('Departure') }}
                    </button>
                    <button
                        wire:click="filterByType('arrival')"
                        type="button"
                        class="text-white {{ $typeFilter === 'arrival' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-700 hover:bg-blue-800' }} focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 cursor-pointer">
                        {{ __('Arrival') }}
                    </button>
                    <button
                        wire:click="toggleCargo"
                        type="button"
                        class="text-white {{ $cargoFilter ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-700 hover:bg-blue-800' }} focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 cursor-pointer">
                        {{ __('Cargo') }}
                    </button>
                </div>
            </div>

            <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">

            <!-- Banner de tipo de ruta con filtros secundarios -->
            <div class="bg-gradient-to-r from-blue-900 to-black w-full rounded-lg overflow-hidden mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center p-4">
                    <h2 class="text-white text-2xl font-bold mb-4 sm:mb-0">
                        @if($typeFilter === 'departure')
                            {{ __('Departure Flights') }}
                        @elseif($typeFilter === 'arrival')
                            {{ __('Arrival Flights') }}
                        @elseif($cargoFilter)
                            {{ __('Cargo Flights') }}
                        @else
                            {{ __('All Flights') }}
                        @endif
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <button
                            wire:click="filterByScope('all')"
                            type="button"
                            class="{{ $scopeFilter === 'all' ? 'bg-blue-700 hover:bg-blue-800' : 'bg-gray-800 hover:bg-gray-700' }} text-white font-medium rounded-full text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300 cursor-pointer">
                            {{ __('All') }}
                        </button>
                        <button
                            wire:click="filterByScope('national')"
                            type="button"
                            class="{{ $scopeFilter === 'national' ? 'bg-blue-700 hover:bg-blue-800' : 'bg-gray-800 hover:bg-gray-700' }} text-white font-medium rounded-full text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-700 cursor-pointer">
                            {{ __('National') }}
                        </button>
                        <button
                            wire:click="filterByScope('international')"
                            type="button"
                            class="{{ $scopeFilter === 'international' ? 'bg-blue-700 hover:bg-blue-800' : 'bg-gray-800 hover:bg-gray-700' }} text-white font-medium rounded-full text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-700 cursor-pointer">
                            {{ __('International') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtros activos -->
            @if(count($activeFilters) > 0)
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active filters') }}:</span>
                @foreach($activeFilters as $filter)
                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 cursor-pointer">
                    {{ $filter }}
                </span>
                @endforeach
                <button
                    wire:click="clearFilters"
                    class="text-xs text-gray-500 hover:text-gray-700 underline dark:text-gray-400 dark:hover:text-gray-300 cursor-pointer">
                    {{ __('Clear all') }}
                </button>
            </div>
            @endif

            <!-- Estado de carga -->
            <div wire:loading class="w-full text-center py-4">
                <div role="status" class="inline-flex">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Tabla de rutas (responsive) -->
            <div wire:loading.remove class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">{{ __('Logo') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Flight') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Origin') }}</th>
                            <th scope="col" class="px-6 py-3 hidden md:table-cell">{{ __('Destination') }}</th>
                            <th scope="col" class="px-6 py-3 hidden lg:table-cell">{{ __('Aircraft') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Time') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Gate') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <th class="px-6 py-4">
                                    @php
                                        $airlineData = app(\App\Services\Ivao::class)->getImagenAirlines($route->airline);
                                        $logoUrl = $airlineData['logo'];
                                    @endphp
                                    <img src="{{ $logoUrl }}" alt="{{ $route->airline }}" class="size-25 object-contain">
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $route->flight_number }}
                                </th>
                                <td class="px-6 py-4">{{ $route->iato_code_origin ?? $route->origin }}</td>
                                <td class="px-6 py-4 hidden md:table-cell">{{ $route->iato_code_destination ?? $route->destination }}</td>
                                <td class="px-6 py-4 hidden lg:table-cell">{{ $route->aircraft_type }}</td>
                                <td class="px-6 py-4">
                                    {{ $route->type === 'departure' ? \Carbon\Carbon::parse($route->hourOrigin)->format('H:i') : \Carbon\Carbon::parse($route->hourDestination)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-[44px] h-[40px] p-2 rounded-lg {{ $route->type === 'departure' ? 'bg-amber-300' : 'bg-green-500' }} text-center text-white font-bold">
                                        {{  $route->gate_origin }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($route->is_active === false)
                                    <a class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-3 py-1.5 focus:outline-none">
                                        {{ __('Reserved') }}
                                    </a>
                                    @else
                                    <a href="{{ route('details.booking', ['hash' => \Illuminate\Support\Facades\Route::obfuscateId($route->id)]) }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-3 py-1.5 focus:outline-none cursor-pointer">
                                        {{ __('Book') }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No routes found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $routes->links() }}
            </div>
        </div>
    </section>
</div>
