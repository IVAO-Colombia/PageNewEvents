<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
        <script>
        (function () {
            function isInternal(a){
                if(!a) return false;
                const href = a.getAttribute('href');
                return href &&
                       !href.startsWith('#') &&
                       !href.startsWith('javascript:') &&
                       !href.startsWith('http') &&
                       !href.startsWith('//') &&
                       !a.hasAttribute('download');
            }
            window.__showPreloader = function () {
                var p = document.getElementById('global-preloader');
                if (p) {
                    p.style.display = 'flex';
                    p.style.opacity = '1';
                }
            };

            document.addEventListener('click', function(e){
                var a = e.target.closest('a');
                if (a && isInternal(a) && !a.hasAttribute('wire:navigate')) {
                    __showPreloader();
                }
            }, true);

            window.addEventListener('beforeunload', function(){
                __showPreloader();
            });
        })();
        </script>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <div id="global-preloader"
             style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;
                    background:#ffffff;z-index:9999;transition:opacity .35s ease">
            <img src="{{ asset('assets/loading/loading_rfe.gif') }}"
                 alt="Loading"
                 style="max-width:160px;width:120px;height:auto;image-rendering:-webkit-optimize-contrast;">
        </div>

        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" >
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" >
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                @if(request()->routeIs('event.details'))
                    <flux:navbar.item icon="document-magnifying-glass"
                                    :href="route('dashboard')"
                                    :current="request()->routeIs('event.details')">
                        {{ __('Events') }}
                    </flux:navbar.item>
                @endif
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:dropdown position="top" align="end">
                    <flux:tooltip :content="__('Translation')" position="bottom">
                        <flux:navbar.item
                            class="h-10 max-lg:hidden [&>div>svg]:size-5"
                            icon="language"
                            :label="__('Translation')"
                        />
                    </flux:tooltip>

                    <flux:menu>
                        <flux:menu.item  :href="route('language.switch', 'en')">
                            English
                        </flux:menu.item>
                        <flux:menu.item  :href="route('language.switch', 'es')">
                            Español
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->fullName() }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" >{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    @if(auth()->check() && auth()->user()->canAccessPanel(app(\Filament\Panel::class)))
                        <flux:menu.item :href="route('filament.admin.pages.dashboard')" icon="arrow-right-end-on-rectangle">
                            {{ __('PanelAdmin') }}
                        </flux:menu.item>
                    @endif

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" >
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" >
                    {{ __('Dashboard') }}
                    </flux:navlist.item>
                    @if(request()->routeIs('event.details'))
                        <flux:navlist.item icon="document-magnifying-glass"
                                        :href="route('dashboard')"
                                        :current="request()->routeIs('event.details')">
                            {{ __('Events') }}
                        </flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>


                <flux:navlist.group :heading="__('Languages')">
                    <flux:navlist.item icon="language" :href="route('language.switch', 'en')">
                        English
                    </flux:navlist.item>
                    <flux:navlist.item icon="language" :href="route('language.switch', 'es')">
                        Español
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
        @stack('scripts')
        <script>
            (function(){
                const start = performance.now();
                const MIN_TIME = 600;
                function hide() {
                    const p = document.getElementById('global-preloader');
                    if(!p) return;
                    const elapsed = performance.now() - start;
                    const wait = Math.max(0, MIN_TIME - elapsed);
                    setTimeout(()=>{
                        p.style.opacity = '0';
                        setTimeout(()=>{ p.style.display='none'; }, 380);
                    }, wait);
                }
                if (document.readyState === 'complete') {
                    hide();
                } else {
                    window.addEventListener('load', hide, { once:true });
                }

                window.addEventListener('pageshow', function(e){
                    if (e.persisted) {
                        const p = document.getElementById('global-preloader');
                        if (p) p.style.display='none';
                    }
                });

                window.hidePreloader = hide;
                window.showPreloader = window.__showPreloader;
            })();
        </script>

    </body>
</html>
