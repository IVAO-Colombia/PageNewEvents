<div class="w-full bg-white dark:bg-gray-800 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('My Reservations') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage all your flight bookings') }}</p>
            </div>
        </div>

        @if(!empty($data) && isset($data['data']))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card de Reservación -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-white">{{ $data['data']['nameevent'] }}</h3>
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">
                                {{ __('Active') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Fecha -->
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-50 dark:bg-gray-600 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Event Date') }}</p>
                                <p class="font-medium dark:text-white">
                                    {{ \Carbon\Carbon::parse($data['data']['dateevent'])->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <!-- Ruta -->
                        <div class="flex items-start mb-4">
                            <div class="bg-blue-50 dark:bg-gray-600 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Flight Route') }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="text-lg font-semibold dark:text-white">{{ $data['data']['departure'] }}</div>
                                    <div class="mx-2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                    <div class="text-lg font-semibold dark:text-white">{{ $data['data']['arrival'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Callsign -->
                        <div class="flex items-center mb-5">
                            <div class="bg-blue-50 dark:bg-gray-600 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Callsign') }}</p>
                                <p class="font-medium dark:text-white">{{ $data['data']['callsing'] }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">

                            <a
                                href="{{ route('details.booking',['hash' => \Illuminate\Support\Facades\Route::obfuscateId($data['data']['routeid'])]) }}"
                                class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors cursor-pointer mb-1"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>

                                {{ __('View Reservation') }}
                            </a>

                            <button
                                type="button"
                                wire:click="CanceledReservation('{{ $data['data']['routeid'] ?? '' }}')"
                                wire:confirm="Are you sure you want to cancel this reservation? This action cannot be undone."
                                class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors cursor-pointer mb-1"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Cancel Reservation') }}
                            </button>

                            <a
                                href="https://dispatch.simbrief.com/options/custom?orig={{ $data['data']['departure'] ?? '' }}&dest={{ $data['data']['arrival'] ?? '' }}"
                                target="_Blank"
                                class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors cursor-pointer"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                                {{ __('Go to Siembrief') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('No Reservations Found') }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('You don\'t have any active flight reservations yet.') }}
                </p>
            </div>
        @endif
    </div>
</div>
