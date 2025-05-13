<div>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <h2 class="text-xl font-semibold text-gray-900  sm:text-2xl">Vuelo Seleccionado</h2>
          <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
            <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
              <div class="space-y-6">

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm -800 md:p-6">
                  <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                    <div class="h-14 w-36 flex items-center justify-center">
                        <img class="max-h-full max-w-full object-contain" src="{{$airline_logo}}" alt="Logo aerolínea" />
                      </div>
                    <div class="flex items-center justify-between md:order-3 md:justify-end">
                      <div class="text-end md:order-4 md:w-32">
                        <p class="text-base font-bold text-gray-900 ">{{$fligth->IcaoAirline}} {{$fligth->flight_number}}</p>
                      </div>
                    </div>
      
                    <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                        <div class="text-base font-medium text-gray-900 flex items-center justify-between gap-3">
                            <span>{{ ucfirst(strtolower($fligth->departure_airport)) }}</span>
                          
                            <!-- Separador con línea y texto -->
                            <span class="relative flex items-center w-full max-w-[80px]">
                              <hr class="w-full h-px bg-gray-200">
                              <span class="absolute left-1/2 -translate-x-1/2 bg-white px-2 text-sm text-gray-400">
                                <span class="material-symbols-outlined text-gray-400 rotate-90">flight</span>
                              </span>
                            </span>
                          
                            <span>{{ucfirst(strtolower($fligth->arrival_airport))}}</span>
                          </div>
                          <div class="my-6">
                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Hora para reserva : {{ \Carbon\Carbon::parse($fligth->departure_time)->format('H:i:s') }}</span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ucfirst(strtolower($fligth->FlightType))}}</span>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </section>

      <hr class="bg-gray-200 border-0 h-px w-1/2 mx-auto" />

    <section class="bg-white">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900">Continua con tu reserva</h2>
            <p class="mb-8 lg:mb-16 font-light text-center text-gray-500  sm:text-xl">Rellena los campos siguientes del formulario para terminar</p>
            <form wire:submit.prevent="submitBooking" class="space-y-8" >
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Correo Electronico</label>
                    <input wire:model="email" type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Example@correo.com" required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Nombre</label>
                    <input wire:model="name" type="text" id="name" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Juancito Pinocho" required >
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer">Reservar</button>
            </form>
        </div>
      </section>
</div>