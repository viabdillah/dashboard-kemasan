<div x-show="sidebarOpen" class="fixed inset-0 z-30 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false"></div>

<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 h-screen px-4 py-8 bg-white border-r dark:bg-gray-800 dark:border-gray-600 shadow-md transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0">

    <a href="{{ route('dashboard') }}" class="flex items-center px-2 text-gray-800 dark:text-white">
        <x-application-logo class="block h-9 w-auto fill-current" />
        <span class="ml-3 text-xl font-bold">{{ config('app.name') }}</span>
    </a>

    <div class="flex flex-col justify-between flex-1 mt-10">
        <nav>
            {{-- ... (Isi <nav> dengan semua x-sidebar-link tetap sama persis seperti sebelumnya) ... --}}
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-home w-6 text-center"></i>
                <span class="mx-4 font-medium">Dashboard</span>
            </x-sidebar-link>
            @if(auth()->user()->hasRole('admin'))
                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <i class="fas fa-users-cog w-6 text-center"></i>
                    <span class="mx-4 font-medium">Manajemen User</span>
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('cashier.orders.index')" :active="request()->routeIs('cashier.orders.index', 'cashier.orders.show')">
                    <i class="fas fa-list-alt w-6 text-center"></i>
                    <span class="mx-4 font-medium">Daftar Pesanan</span>
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('cashier.orders.create')" :active="request()->routeIs('cashier.orders.create')">
                    <i class="fas fa-plus-circle w-6 text-center"></i>
                    <span class="mx-4 font-medium">Buat Pesanan Baru</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('cashier.orders.payments')" :active="request()->routeIs('cashier.orders.payments')">
                    <i class="fas fa-hand-holding-usd w-6 text-center"></i>
                    <span class="mx-4 font-medium">Pembayaran</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('cashier.orders.history')" :active="request()->routeIs('cashier.orders.history')">
                    <i class="fas fa-history w-6 text-center"></i>
                    <span class="mx-4 font-medium">Riwayat Pesanan</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('cashier.cash-flow.index')" :active="request()->routeIs('cashier.cash-flow.index')">
                    <i class="fas fa-book w-6 text-center"></i>
                    <span class="mx-4 font-medium">Buku Kas</span>
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('designer'))
                <x-sidebar-link :href="route('designer.orders.index')" :active="request()->routeIs('designer.orders.*')">
                    <i class="fas fa-paint-brush w-6 text-center"></i>
                    <span class="mx-4 font-medium">Tugas Desain</span>
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('operator'))
                <x-sidebar-link :href="route('operator.production.index')" :active="request()->routeIs('operator.production.index')">
                    <i class="fas fa-cogs w-6 text-center"></i>
                    <span class="mx-4 font-medium">Antrian Produksi</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('operator.production.history')" :active="request()->routeIs('operator.production.history')">
                    <i class="fas fa-history w-6 text-center"></i>
                    <span class="mx-4 font-medium">Riwayat Produksi</span>
                </x-sidebar-link>

                <div x-data="{ open: request()->routeIs('operator.products.*') || request()->routeIs('operator.spare-parts.*') }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 mt-2 text-gray-600 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-200 hover:text-gray-700 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-warehouse w-6 text-center"></i>
                            <span class="mx-4 font-medium">Inventory</span>
                        </div>
                        <div>
                            <svg class="w-4 h-4" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-cloak class="mt-2 space-y-2 pl-8" x-transition>
                        <a href="{{ route('operator.products.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('operator.products.*') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            Daftar Barang
                        </a>
                        <a href="{{ route('operator.spare-parts.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('operator.spare-parts.*') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            Daftar Spare Part
                        </a>
                    </div>
                </div>
            @endif
            @if(auth()->user()->hasRole('manajer'))
                <x-sidebar-link :href="route('manager.reports.financial')" :active="request()->routeIs('manager.reports.financial')">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="mx-4 font-medium">Laporan Keuangan</span>
                </x-sidebar-link>
                <x-sidebar-link :href="route('manager.reports.activity_log')" :active="request()->routeIs('manager.reports.activity_log')">
                    <i class="fas fa-history w-6 text-center"></i>
                    <span class="mx-4 font-medium">Log Aktivitas</span>
                </x-sidebar-link>
            @endif
        </nav>
    </div>
</aside>