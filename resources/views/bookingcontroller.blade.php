<x-layouts.app :title="__('Controller')">
      <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                <div class="max-w-screen-md">
                    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">{{ __('Reserve Your Position') }}</h2>
                    <p class="mb-8 font-light text-gray-500 sm:text-xl dark:text-gray-400">{{ __('Before Booking') }}</p>
                </div>
            </div>
        </section>
        <livewire:bookingcontroller :bookingId="$routeId" />
      </div>
      <x-navigation.footer />
</x-layouts.app>
