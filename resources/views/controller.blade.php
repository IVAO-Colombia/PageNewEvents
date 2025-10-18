<x-layouts.app :title="__('Controller')">
      <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="max-w-screen-md mb-8 lg:mb-16">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">{{ __('Designed for controllers who guide the skies of IVAO Colombia.') }}</h2>
                <p class="text-gray-500 sm:text-xl dark:text-gray-400">{{ __('At IVAO Colombia, we are committed to providing the best possible experience for our controllers.') }}</p>
            </div>
            <livewire:eventcontroller />
        </div>
        </section>
      </div>
      <x-navigation.footer />
</x-layouts.app>
