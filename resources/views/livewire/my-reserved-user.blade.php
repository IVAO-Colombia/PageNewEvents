<div>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <div class="mx-auto max-w-5xl">
            <div class="gap-4 sm:flex sm:items-center sm:justify-between">
              <h2 class="text-xl font-semibold text-gray-900  sm:text-2xl">Mis Reservas</h2>
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-white">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compañia</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha de reserva</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                      <th class="px-6 py-3"></th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                   @foreach($bookings as $booking)
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">                     
                        <div class="h-14 w-36 flex items-center justify-center">
                        <img class="max-h-full max-w-full object-contain" src="{{ $booking->airline_logo }}" alt="{{ $booking->airline_name }}" />
                      </div></td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->created_at }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        @if($booking->is_cancelled == 1)
                          <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Cancelada</span>
                        @else
                          <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Activa</span>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <a x-data @click="window.dispatchEvent(new CustomEvent('alert_delete', { detail: { id: {{ $booking->id }} } }))"
                          class="text-red-600 border border-red-600 hover:bg-red-50 font-medium rounded-lg text-sm px-4 py-2 cursor-pointer">
                          Cancelar Reserva
                       </a>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <button wire:click="showBooking({{ $booking->id }})"
                          class="text-blue-700 border border-gray-200 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2 cursor-pointer">
                          Check
                      </button>
                      </td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
          </div>
        </div>
      </section>

      <!-- Main modal -->
      @if($selectedBooking)
      <div
      id="default-modal"
      tabindex="-1"
      class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full  bg-opacity-80  backdrop-blur-sm"
      >
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <div class="h-14 w-36 flex items-center justify-center">
                            <img class="max-h-full max-w-full object-contain" src="{{ $selectedBooking['airline_logo'] }}" alt="{{ $selectedBooking['airline_name'] }}" />
                          </div>
                    </h3>
                <button type="button" wire:click="$set('selectedBooking', null)" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer transition-all duration-200 ease-in-out">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <h2 class="text-4xl font-bold mx-3">{{ $selectedBooking['fligth']['IcaoAirline'] ?? '' }}{{ $selectedBooking['fligth']['flight_number'] ?? 'N/A' }}</h2>
                    <br>
                    @if(isset($selectedBooking['fligth']) && $selectedBooking['fligth']['GateArrival'] == NULL)
                    <ol class="relative border-s border-gray-200  ms-3.5 mb-4 md:mb-5">                  
                      <li class="mb-10 ms-8">            
                          <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full -start-3.5 ring-8 ring-white">
                              <svg class="w-2.5 h-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                  <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                                </svg>  
                          </span>
                          <h3 class="flex items-start mb-1 text-lg font-semibold text-gray-900">{{$selectedBooking['fligth']['departure_airport']}}</h3>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Puerta de Salida: <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $selectedBooking['fligth']['GateDeparture'] ?? '[]' }}</span> </time>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Hora de Salida: {{ \Carbon\Carbon::parse($selectedBooking['fligth']['departure_time'])->format('H:i:s') ?? '00:00:00' }}</time>
                      </li>
                      <li class="ms-8">
                          <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full -start-3.5 ring-8 ring-white">
                              <svg class="w-2.5 h-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                  <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm5.757-1a1 1 0 1 0 0 2h8.486a1 1 0 1 0 0-2H7.757Z" clip-rule="evenodd"/>
                                </svg>                                      
                          </span>
                          <h3 class="mb-1 text-lg font-semibold text-gray-900">{{ $selectedBooking['fligth']['arrival_airport'] }}</h3>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Puerta de LLegada: Sin Informacion </time>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Hora de llegada: Desconocida</time>
                      </li>
                  </ol>
                    @else
                    <ol class="relative border-s border-gray-200  ms-3.5 mb-4 md:mb-5">                  
                      <li class="mb-10 ms-8">            
                          <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full -start-3.5 ring-8 ring-white">
                              <svg class="w-2.5 h-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                  <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                                </svg>  
                          </span>
                          <h3 class="flex items-start mb-1 text-lg font-semibold text-gray-900">{{$selectedBooking['fligth']['departure_airport']}}</h3>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Puerta de Salida: Sin Informacion </time>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Hora de Salida:  {{ \Carbon\Carbon::parse($selectedBooking['fligth']['departure_time'])->format('H:i:s') ?? '00:00:00' }}</time>
                      </li>
                      <li class="ms-8">
                          <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full -start-3.5 ring-8 ring-white">
                              <svg class="w-2.5 h-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                  <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm5.757-1a1 1 0 1 0 0 2h8.486a1 1 0 1 0 0-2H7.757Z" clip-rule="evenodd"/>
                                </svg>                                      
                          </span>
                          <h3 class="mb-1 text-lg font-semibold text-gray-900">{{ $selectedBooking['fligth']['arrival_airport'] }}</h3>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Puerta de LLegada: <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $selectedBooking['fligth']['GateArrival'] ?? '[]' }}</span> </time>
                          <time class="block mb-3 text-sm font-normal leading-none text-gray-500">Hora de llegada: Desconocida</time>
                      </li>
                  </ol>
                    @endif
                </div>

                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500">
                        <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 ">Nombre Completo</label>
                            <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md  ">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                </svg>
                            </span>
                            <input type="text" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 " placeholder="{{ $selectedBooking['user_name'] }}" disabled>
                        </div>
                    </p>
                    <p class="text-base leading-relaxed text-gray-500">
                        <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 ">ID IVAO</label>
                        <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md  ">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                        </span>
                        <input type="text" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 " placeholder="{{ $selectedBooking['ivao_id'] }}" disabled>
                    </div>
                    </p>
                </div>

                <div class="p-4 md:p-5">
                    <p class="text-sm font-normal text-gray-500">Conéctese con uno de nuestros proveedores.</p>
                    <ul class="my-4 space-y-3">
                        <li>
                            <a href="https://www.simbrief.com/system/dispatch.php?orig={{ $selectedBooking['fligth']['IcaoDeparture'] }}&dest={{ $selectedBooking['fligth']['IcaoArrival'] }}&airline={{ $selectedBooking['fligth']['IcaoAirline'] }}&fltnum={{ $selectedBooking['fligth']['flight_number'] }}" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow">
                               <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHl9tBe-m8_y2hZCeZM9jox3_qgwYvVL1TUg&s" class="w-6 h-6" alt="">
                                <span class="flex-1 ms-3 whitespace-nowrap">Siembrief</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- End Modal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      window.addEventListener('alert_delete', function (event) {
          const bookingId = event.detail.id;

          Swal.fire({
              title: "¿Desea cancelar esta reserva?",
              text: "¡No podrás reversar esta reserva!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Sí, cancelar reserva"
          }).then((result) => {
              if (result.isConfirmed) {
                Livewire.dispatch('deleteBookingConfirmed', { id: bookingId });
              }
          });
      });

      window.addEventListener('booking_deleted', function () {
          Swal.fire(
              "Reservación cancelada!",
              "Tu reserva ha sido cancelada.",
              "success"
          );
      });
  </script>
</div>
