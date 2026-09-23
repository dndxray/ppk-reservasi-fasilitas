<nav x-data="{ open: false }" class="bg-[#511E1D] sticky top-0 z-30 shadow-sm">
    <div class="max-w-full mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-full overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('images/logo-trans.png') }}" alt="Logo Loka" class="w-7 h-7 object-contain">
                    </div>
                    <span class="text-white text-2xl font-bold" style="font-family: 'MuseoModerno', sans-serif;">LOKA</span>
                </a>
            </div>
            <div class="flex items-center gap-4">

                <!-- notifikasi -->
                 @auth
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                @endauth

                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 focus:outline-none">
                            <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-white text-sm font-semibold leading-tight">{{ Auth::user()?->name }}</div>
                                <div class="text-white/70 text-xs leading-tight">
                                    @if(Auth::user()->isAdmin()) Admin
                                    @elseif(Auth::user()->isPetugas()) Petugas
                                    @else Pengguna
                                    @endif
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-white text-sm font-semibold" style="font-family: 'Plus Jakarta Sans', sans-serif;">Masuk</a>
                    <a href="{{ route('register') }}" class="text-white text-sm font-semibold" style="font-family: 'Plus Jakarta Sans', sans-serif;">Daftar</a>
                </div>
                @endauth

            </div>
        </div>
    </div>
</nav>