<div>
    <header>
        <!-- Navbar -->
        <nav class="bg-white border-gray-200">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="{{url('/')}}" class="flex items-center">
                    <img src="{{ asset('Images/LogoTag.svg') }}" class="h-16 w-auto" alt="Ivao Co Logo" />
                </a>

                <!-- Botones visibles solo en pantallas grandes -->
                <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
                    @auth
                    @if (Request::is('profile'))

                    @livewire('buttom-logout')

                    @else
                    @if(Auth::user()->is_admin == 1)
                    <a type="button" href="{{ url('admin') }}"
                        class="hidden md:inline-flex py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700 focus:ring-4 focus:ring-gray-100">
                        Panel de Control
                    </a>
                    @endif
                    <a type="button" href="{{ route('profile') }}"
                        class="hidden md:inline-flex py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700 focus:ring-4 focus:ring-gray-100">
                        Ver Perfil
                    </a>
                    @endif
                    @else
                    <a type="button"
                        class="hidden md:inline-flex text-white bg-[#1da1f2] hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center items-center me-2 mb-2 cursor-pointer"
                        href="{{ route('login') }}">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 20a7.966 7.966 0 0 1-5.002-1.756v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z"
                                clip-rule="evenodd" />
                        </svg>
                        Iniciar Sesión con IVAO
                    </a>
                    @endauth

                    <!-- Botón Hamburguesa -->
                    <button data-collapse-toggle="navbar-cta" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        aria-controls="navbar-cta" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>

                <!-- Menú colapsable -->
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                        <li>
                            <x-navbar-link
                                :active="request()->routeIs('eventos') || request()->routeIs('event.detail') || request()->routeIs('event.flights')"
                                href="{{url('eventos')}}">Eventos </x-navbar-link>
                        </li>
                        <li>
                            <button id="dropdownPilotoLink" data-dropdown-toggle="dropdownPiloto"
                                class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:p-0 md:w-auto cursor-pointer poppins-medium">Piloto
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="dropdownPiloto"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 ">
                                <ul class="py-2 text-sm text-gray-700">
                                    <li><a href="{{ route('eventos') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 poppins-regular">Reservar</a></li>
                                    <li><a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 poppins-regular">Información</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <button id="dropdownControladorLink" data-dropdown-toggle="dropdownControlador"
                                class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:p-0 md:w-auto cursor-pointer poppins-medium">Controlador
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="dropdownControlador"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                                <ul class="py-2 text-sm text-gray-700">
                                    <li><a href="https://atc.ivao.aero/"
                                            class="block px-4 py-2 hover:bg-gray-100 poppins-regular">Reservar</a></li>
                                    <li><a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 poppins-regular">Información</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{-- <li><a href="#"
                                class="block py-2 px-3 md:p-0 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 poppins-medium">Estadística</a>
                        </li> --}}

                        <!-- 👇 Botones visibles solo en menú hamburguesa -->
                        <li class="md:hidden mt-3">
                            @auth
                            @if (Request::is('profile'))
                            @livewire('buttom-logout')
                            @else
                            <a type="button" href="{{ route('profile') }}"
                                class="hidden md:inline-flex py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700 focus:ring-4 focus:ring-gray-100">
                                Ver Perfil
                            </a>
                            @endif
                            @else
                            <a type="button"
                                class="hidden md:inline-flex text-white bg-[#1da1f2] hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center items-center me-2 mb-2 cursosr-pointer"
                                href="{{ route('login') }}">
                                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M12 20a7.966 7.966 0 0 1-5.002-1.756v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Iniciar Sesión con IVAO
                            </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
    </header>
</div>