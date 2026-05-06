@if ($event)
<div class="relative w-full h-screen"
    style=" background-image: url('{{ asset('storage/'.$event->imagen) }}'); background-size: cover; background-position: center; ">

    <div class="absolute inset-0 bg-black/40 z-5"></div>

    <x-navigation.header />


    <div class="absolute inset-0 z-10">
        <div class="flex flex-col justify-center h-full px-8 md:px-16 lg:px-24 mx-auto">
            <div class="max-w-2xl mx-auto sm:ml-16 md:ml-32 lg:ml-48">
                @isset($event)
                <div class="mb-10">
                    <h1 class="text-6xl md:text-6xl font-bold text-white leading-tight nunito-sans-extrabold">
                        <br>{{ $event->name }}
                    </h1>
                    <h2 class="text-xl md:text-2xl text-white font-medium mt-2 mb-1 nunito-sans-medium">
                        {{ $event->name_airport }}
                    </h2>
                    <div class=" rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-white mb-3">{{ __('Time Remaining') }}
                        </h3>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 lg:gap-8"
                            id="countdown-container">
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                                <span class="block text-3xl lg:text-4xl font-bold text-[#0D2C99]"
                                    id="countdown-days">0</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">days</span>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                                <span class="block text-3xl lg:text-4xl font-bold text-[#0D2C99]"
                                    id="countdown-hours">0</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">hours</span>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                                <span class="block text-3xl lg:text-4xl font-bold text-[#0D2C99]"
                                    id="countdown-minutes">0</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">min</span>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow px-4 py-2 text-center min-w-[80px]">
                                <span class="block text-3xl lg:text-4xl font-bold text-[#0D2C99]"
                                    id="countdown-seconds">0</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">sec</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('event.details', $event->slug) }}"
                        class="inline-block bg-[#0D2C99] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('View Event') }} </a>
                </div>
                @else
                <div class="text-white text-center py-10">
                    <p class="text-6xl">{{ __('No events available at this time') }}</p>
                </div>
                @endisset
            </div>

        </div>
    </div>

    <script>
        const eventDate = new Date("{{ $event->start_time }}").getTime();
        console.log("{{$event}}")

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
</div>
@else

<div class="relative w-full h-[60vh] bg-[#0D2C99] flex items-center justify-center">

    <x-navigation.header />

    <div class="text-center">
        <h1 class="text-6xl font-bold text-white mb-4">{{ __('No events available at this time') }}</h1>
        <p class="text-xl text-gray-300">{{ __('Please check back later for upcoming events.') }}</p>
    </div>
</div>

@endif