<aside
    id="main-sidebar"
    :class="[
        sidebarOpen ? 'w-64' : 'w-20',
        transitionsEnabled ? 'transition-all duration-200' : ''
    ]"
    class="bg-[#3d1513] text-white flex-shrink-0 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto overflow-x-hidden"
>
    <div class="flex py-4 px-4 sidebar-toggle-box" :class="sidebarOpen ? 'justify-end' : 'justify-center'">
        <button type="button" @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-gray-200 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <nav class="py-2 space-y-1">

        <a href="{{ route('beranda') }}"
           class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                  {{ request()->routeIs('beranda') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Beranda</span>
        </a>

        @auth
            @if(Auth::user()->isPengguna())
                <a href="{{ route('fasilitas.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('fasilitas.*') || request()->routeIs('reservations.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Reservasi</span>
                </a>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('reports.index') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Laporan Saya</span>
                </a>
            @endif

            @if(Auth::user()->isPetugas())
                <a href="{{ route('petugas.reservations.queue') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('petugas.reservations.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Antrian Reservasi</span>
                </a>

                <a href="{{ route('reports.antrian') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('reports.antrian') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Antrian Laporan</span>
                </a>
            @endif

            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.fasilitas.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('admin.fasilitas.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21h2m0 0h10" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Kelola Fasilitas</span>
                </a>

                <a href="{{ route('admin.pengguna.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('admin.pengguna.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Kelola Pengguna</span>
                </a>

                <a href="{{ route('admin.petugas.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('admin.petugas.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14v7m-4-4h8" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Kelola Petugas</span>
                </a>

                <a href="{{ route('admin.rekap.index') }}"
                   class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                          {{ request()->routeIs('admin.rekap.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-9 0h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Rekap</span>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                      {{ request()->routeIs('profile.*') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Profil</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex items-center gap-3 px-5 py-3 mx-2 rounded-lg transition
                      {{ request()->routeIs('login') ? 'bg-[#511E1D]' : 'hover:bg-[#511E1D]/60' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span x-show="sidebarOpen" class="sidebar-label text-sm font-medium whitespace-nowrap">Login</span>
            </a>
        @endauth

    </nav>
</aside>