<div class="max-w-2xl mx-auto sm:ml-16 md:ml-32 lg:ml-48">
    @forelse($events as $event)
        <div class="mb-10">
            <h1 class="text-6xl md:text-6xl font-bold text-white leading-tight nunito-sans-extrabold">
                <br>{{ $event->name }}
            </h1>
            <h2 class="text-xl md:text-2xl text-white font-medium mt-2 mb-1 nunito-sans-medium">
                {{ $event->name_airport }}
            </h2>
            <p class="text-lg text-white mb-6">{{ $event->start_time }}</p>
        </div>
    @empty
        <div class="text-white text-center py-10">
            <p class="text-6xl">{{ __('No events available at this time') }}</p>
        </div>
    @endforelse
</div>
