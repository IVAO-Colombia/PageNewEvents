<header class="z-50 w-full py-4 absolute top-0 left-0">
  <nav class="max-w-7xl w-full flex justify-between items-center px-4 md:px-6 lg:px-8 mx-auto">
    <div class="flex items-center">
      <!-- Logo -->
         <img src={{ asset('assets/images/Logo.png') }} width="80px" height="80px" alt="Logo IVAO CO">
      <!-- End Logo -->
    </div>
    <!-- Button Group -->
    <div class="flex items-center gap-x-1 lg:gap-x-2 py-1">
      <div class="relative">
        <button type="button" id="translate-btn" class="size-9.5 relative flex justify-center items-center rounded-xl border border-white text-white hover:bg-white hover:text-black focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 transition cursor-pointer" aria-label="Change language">
          <span class="material-symbols-outlined">
          translate
          </span>
        </button>
        <div id="language-dropdown" class="hidden absolute right-0 mt-2 bg-white rounded-md shadow-lg z-50">
            <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
            <a href="{{ route('language.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
        </div>
      </div>
      <a id="login-button" href="{{ route('login') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-white text-white focus:outline-hidden transition disabled:opacity-50 cursor-pointer hover:bg-white hover:text-black nunito-sans-semibold" aria-label="Login">
        {{ __('Login') }}
      </a>
    </div>
  </nav>
</header>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
@endpush
