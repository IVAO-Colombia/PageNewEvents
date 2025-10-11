<x-layouts.app :title="__('EventsPost')">
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.umd.min.js"></script>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:detailevent :eventId="$eventId" />
    </div>
    <x-navigation.footer />
</x-layouts.app>
