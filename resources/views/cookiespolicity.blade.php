<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <title>{{ __('Title') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=translate" />


        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Estilos para el preloader */
            .preloader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #fffcfc;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
            }

            .preloader.hidden {
                opacity: 0;
                visibility: hidden;
            }

            .preloader img {
                max-width: 150px;
                max-height: 150px;
            }
        </style>

</head>
<body>

        <!-- Preloader -->
        <div id="preloader" class="preloader">
            <img src="{{ asset('assets/loading/loading_rfe.gif') }}" alt="Cargando...">
        </div>

        <header class="w-full py-4  top-0 left-0">
            <nav class="max-w-7xl w-full flex justify-between items-center px-4 md:px-6 lg:px-8 mx-auto">
                <div class="flex items-center">
                <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src={{ asset('assets/images/symbol.png') }} width="80px" height="80px" alt="Logo IVAO CO">
                    </a>
                <!-- End Logo -->
                </div>
                <!-- Button Group -->
                <div class="flex items-center gap-x-1 lg:gap-x-2 py-1">
                <div class="relative">
                    <button type="button" id="translate-btn" class="size-9.5 relative flex justify-center items-center rounded-xl border border-black text-black hover:bg-black hover:text-white focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 transition cursor-pointer" aria-label="Change language">
                    <span class="material-symbols-outlined">
                    translate
                    </span>
                    </button>
                    <div id="language-dropdown" class="hidden absolute right-0 mt-2 bg-white rounded-md shadow-lg z-50">
                        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                        <a href="{{ route('language.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                    </div>
                </div>
                </div>
            </nav>
            </header>


        <!-- Blog Article -->
        <div class="max-w-3xl px-4 pt-6 lg:pt-10 pb-12 sm:px-6 lg:px-8 mx-auto">
            <div class="max-w-2xl">
                <!-- Avatar Media -->
                <div class="flex justify-between items-center mb-6">
                <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                    <div class="shrink-0">
                    <img class="size-12 rounded-full" src="{{ asset('assets/images/symbol.png') }}" alt="Avatar">
                    </div>

                    <div class="grow">
                    <div class="flex justify-between items-center gap-x-2">
                        <div>
                        <!-- Tooltip -->
                        <div class="hs-tooltip [--trigger:hover] [--placement:bottom] inline-block">
                            <div class="hs-tooltip-toggle sm:mb-1 block text-start cursor-pointer">
                            <span class="font-semibold text-gray-800 poppins-medium">
                               {{__('CookiesPublishName')}}
                            </span>

                            <!-- Dropdown Card -->
                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 max-w-xs cursor-default bg-gray-900 divide-y divide-gray-700 shadow-lg rounded-xl" role="tooltip">
                                <!-- Body -->
                                <div class="p-4 sm:p-5">
                                <div class="mb-2 flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                                    <div class="shrink-0">
                                    <img class="size-8 rounded-full" src="https://images.unsplash.com/photo-1669837401587-f9a4cfe3126e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="Avatar">
                                    </div>

                                    <div class="grow">
                                    <p class="text-lg font-semibold text-gray-200">
                                        Leyla Ludic
                                    </p>
                                    </div>
                                </div>
                                </div>
                                <!-- End Body -->
                            </div>
                            <!-- End Dropdown Card -->
                            </div>
                        </div>
                        <!-- End Tooltip -->

                        <ul class="text-xs text-gray-500">
                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full poppins-regular">
                            {{ __('CookiesPublishDate') }}
                            </li>
                            <li class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full poppins-regular">
                            1 min read
                            </li>
                        </ul>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Avatar Media -->

                <!-- Content -->
                <div class="space-y-5 md:space-y-8 mb-4">
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold md:text-3xl nunito-sans-bold">{{ __('CookiesTitle') }}</h2>

                    <p class="text-lg text-gray-800 poppins-regular">{{ __('CookiesContentOne') }}</p>
                </div>

                <p class="text-lg text-gray-800 poppins-regular">{{ __('CookiesContentTwo') }}</p>
                <p class="text-lg text-gray-800 poppins-regular">{{ __('CookiesContentThree') }}</p>

                 <a class="text-blue-600 hover:underline poppins-regular" href="https://wiki.ivao.aero/en/home/ivao/privacypolicy">{{ __('GDPR') }}</a>
                </div>

                <!-- End Content -->
                <a href="{{ route('home') }}" class="py-4 px-4 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none poppins-bold">
                    Volver al Inicio
                </a>
            </div>
        </div>
        <!-- End Blog Article -->

        <script>
            const loaderTimeout = setTimeout(function() {
                const preloader = document.getElementById('preloader');
                if (!preloader.classList.contains('hidden')) {
                    preloader.classList.add('hidden');
                    setTimeout(() => preloader.style.display = 'none', 500);
                }
            }, 10000);

            window.addEventListener('load', function() {
                clearTimeout(loaderTimeout);
                const preloader = document.getElementById('preloader');
                preloader.classList.add('hidden');
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Script loaded');
                const translateBtn = document.getElementById('translate-btn');
                const dropdown = document.getElementById('language-dropdown');

                translateBtn.addEventListener('click', function() {
                dropdown.classList.toggle('hidden');
                });


                document.addEventListener('click', function(event) {
                if (!translateBtn.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
                });
            });
        </script>
    
</body>
</html>