<div class="min-h-screen py-8">
    <!-- Hero Section with Image and Countdown -->
    <div class="container mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg">
            <!-- Image with overlay for mobile -->
            <div class="relative">
                <img class="w-full h-64 md:h-80 lg:h-96 object-cover" src="{{ Storage::url($event->imagen) }}" alt="{{ $event->name }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent md:hidden flex flex-col justify-end p-4">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $event->name }}</h1>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- Title (hidden on mobile since it appears on overlay) -->
                <h1 class="text-4xl md:text-5xl font-bold mb-4 hidden md:block">{{ $event->name }}</h1>

                <!-- Countdown Section - Responsive Design -->
                <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200 mb-3">{{ __('Time Remaining') }}</h3>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 lg:gap-8" id="countdown-container">
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                            <span class="block text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400" id="countdown-days">0</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">days</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                            <span class="block text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400" id="countdown-hours">0</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">hours</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                            <span class="block text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400" id="countdown-minutes">0</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">min</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                            <span class="block text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400" id="countdown-seconds">0</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">sec</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Section - Responsive Grid -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Stats') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('StatsTotal') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['available'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('StatsAvailable') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['not_available'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('StatsNotAvailable') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="prose dark:prose-invert max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('About This Event') }}</h2>
                        <div class="text-gray-700 dark:text-gray-300">{!! $trasanslation !!}</div>
                </div>

                <!-- Map Section -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Event Location') }}</h2>
                    <div wire:ignore id="map" class="w-full h-64 md:h-96 rounded-lg overflow-hidden shadow-lg"></div>
                </div>

                <div  class="flex justify-center md:justify-start mt-5 mb-4">
                        <a href="{{ route('search.bookings', ['id' => $event->name]) }}" class="px-6 py-3.5 text-base font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                            <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                            <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                            </svg>
                          {{ __('Reserve Your Spot') }}
                        </a>
                </div>
        </div>
    </div>
</div>
<script>
    const map = new maptilersdk.Map({
        container: 'map', // container id
        style: @json($mapStyle), // style URL
        center: [{{ $event->longitude }}, {{ $event->latitude }}],
        zoom: 13, // starting zoom
    });
    const marker = new maptilersdk.Marker()
        .setLngLat([{{ $event->longitude }}, {{ $event->latitude }}])
        .addTo(map);
</script>

<script>
    const eventDate = new Date("{{ $event->start_time }}").getTime();

    const countdownTimer = setInterval(function() {
        const now = new Date().getTime();

        const distance = eventDate - now;
        if (distance < 0) {
            clearInterval(countdownTimer);
            document.getElementById("countdown-container").innerHTML =
                "<div class='text-xl font-bold text-red-600 text-center w-full'>¡El evento ya comenzó!</div>";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown-days").textContent = days;
        document.getElementById("countdown-hours").textContent = hours;
        document.getElementById("countdown-minutes").textContent = minutes;
        document.getElementById("countdown-seconds").textContent = seconds;
    }, 1000);
</script>
