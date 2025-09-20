<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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


        <div class="relative w-full h-screen">
            <img src="{{ asset('assets/images/Background.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover z-0">

            <!-- Overlay para oscurecer ligeramente la imagen -->
            <div class="absolute inset-0 bg-black/10 z-5"></div>

            <!-- Header con navegación -->
            <x-navigation.header />

            <!-- Contenido principal -->
            <div class="absolute inset-0 z-10">
                <div class="flex flex-col justify-center h-full px-8 md:px-16 lg:px-24 mx-auto">
                    <div class="max-w-2xl mx-auto sm:ml-16 md:ml-32 lg:ml-48">
                        <h1 class="text-6xl md:text-6xl font-bold text-white leading-tight">
                            2025<br>RFO MEDELLIN
                        </h1>
                        <h2 class="text-xl md:text-2xl text-white font-medium mt-2 mb-1">
                            Aeropuerto Internacional José María Córdova
                        </h2>
                        <p class="text-lg text-white mb-6">DIA AGOSTO 25</p>
                        <a href="#" class="inline-block px-6 py-2 border border-white text-white rounded-md hover:bg-white hover:text-black transition duration-300">
                            {{ __('View More') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>



        @stack('scripts')
        <script src="./node_modules/preline/dist/preline.js"></script>
        <script>
            const loaderTimeout = setTimeout(function() {
                const preloader = document.getElementById('preloader');
                if (!preloader.classList.contains('hidden')) {
                    preloader.classList.add('hidden');
                    setTimeout(() => preloader.style.display = 'none', 500);
                }
            }, 10000); // 10 segundos

            window.addEventListener('load', function() {
                clearTimeout(loaderTimeout); // Cancela el timeout si la carga fue normal
                const preloader = document.getElementById('preloader');
                preloader.classList.add('hidden');
                // resto del código...
            });
        </script>
    </body>
</html>
