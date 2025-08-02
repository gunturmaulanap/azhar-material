<div class="flex gap-x-4 items-center border-b p-4">
    <svg width="60" height="60" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M35.3498 37.2751C35.1456 37.2459 34.8831 37.2459 34.6498 37.2751C29.5164 37.1001 25.4331 32.9001 25.4331 27.7376C25.4331 22.4584 29.6914 18.1709 34.9998 18.1709C40.2789 18.1709 44.5664 22.4584 44.5664 27.7376C44.5373 32.9001 40.4831 37.1001 35.3498 37.2751Z"
            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path
            d="M54.6585 56.525C49.4668 61.2792 42.5835 64.1667 35.0001 64.1667C27.4168 64.1667 20.5335 61.2792 15.3418 56.525C15.6335 53.7834 17.3835 51.1 20.5043 49C28.496 43.6917 41.5626 43.6917 49.496 49C52.6168 51.1 54.3668 53.7834 54.6585 56.525Z"
            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path
            d="M35.0002 64.1666C51.1085 64.1666 64.1668 51.1082 64.1668 34.9999C64.1668 18.8916 51.1085 5.83325 35.0002 5.83325C18.8919 5.83325 5.8335 18.8916 5.8335 34.9999C5.8335 51.1082 18.8919 64.1666 35.0002 64.1666Z"
            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <div>
        <p class="whitespace-nowrap mr-9 pr-4 leading-none uppercase">{{ Auth::user()->name }}</p>
        <p class="whitespace-nowrap mr-9 pr-4 leading-tight uppercase text-gray-300">{{ Auth::user()->name }}</p>
    </div>
</div>
<nav class="flex flex-col space-y-4 py-4">

    @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path
                    d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                <path
                    d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
            </svg>
            <span class="mx-2 text-sm font-medium">Dashboard</span>
        </x-nav-link>
    @endif

    @if (auth()->user()->role === 'super_admin')
        <x-side-dropdown :active="request()->routeIs('master.*')">
            <x-slot name="trigger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path
                        d="M11.644 1.59a.75.75 0 0 1 .712 0l9.75 5.25a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.712 0l-9.75-5.25a.75.75 0 0 1 0-1.32l9.75-5.25Z" />
                    <path
                        d="m3.265 10.602 7.668 4.129a2.25 2.25 0 0 0 2.134 0l7.668-4.13 1.37.739a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.71 0l-9.75-5.25a.75.75 0 0 1 0-1.32l1.37-.738Z" />
                    <path
                        d="m10.933 19.231-7.668-4.13-1.37.739a.75.75 0 0 0 0 1.32l9.75 5.25c.221.12.489.12.71 0l9.75-5.25a.75.75 0 0 0 0-1.32l-1.37-.738-7.668 4.13a2.25 2.25 0 0 1-2.134-.001Z" />
                </svg>

                <span class="mx-2 text-sm font-medium">Data Master</span>
            </x-slot>
            <x-slot name="content">
                <x-side-dropdown-link :href="route('master.admin')" :active="request()->routeIs('master.admin') ||
                    request()->routeIs('master.create-admin') ||
                    request()->routeIs('master.update-admin')">
                    Data Admin
                </x-side-dropdown-link>
                <x-side-dropdown-link :href="route('master.employee')" :active="request()->routeIs('master.employee') ||
                    request()->routeIs('master.create-employee') ||
                    request()->routeIs('master.update-employee')">
                    Data Pegawai
                </x-side-dropdown-link>
                <x-side-dropdown-link :href="route('master.supplier')" :active="request()->routeIs('master.supplier') ||
                    request()->routeIs('master.create-supplier') ||
                    request()->routeIs('master.update-supplier')">
                    Data Supplier
                </x-side-dropdown-link>
                <x-side-dropdown-link :href="route('master.customer')" :active="request()->routeIs('master.customer') ||
                    request()->routeIs('master.create-customer') ||
                    request()->routeIs('master.update-customer') ||
                    request()->routeIs('master.detail-customer')">
                    Data Customer
                </x-side-dropdown-link>
            </x-slot>
        </x-side-dropdown>

        <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd"
                    d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z"
                    clip-rule="evenodd" />
                <path fill-rule="evenodd"
                    d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375Zm9.586 4.594a.75.75 0 0 0-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 0 0-1.06 1.06l1.5 1.5a.75.75 0 0 0 1.116-.062l3-3.75Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="mx-2 text-sm font-medium">Absensi</span>
        </x-nav-link>
    @endif

    @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
        <x-side-dropdown :active="request()->routeIs('transaction.*') ||
            request()->routeIs('delivery.*') ||
            request()->routeIs('debt.*')">
            <x-slot name="trigger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path
                        d="M7.5 3.375c0-1.036.84-1.875 1.875-1.875h.375a3.75 3.75 0 0 1 3.75 3.75v1.875C13.5 8.161 14.34 9 15.375 9h1.875A3.75 3.75 0 0 1 21 12.75v3.375C21 17.16 20.16 18 19.125 18h-9.75A1.875 1.875 0 0 1 7.5 16.125V3.375Z" />
                    <path
                        d="M15 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 17.25 7.5h-1.875A.375.375 0 0 1 15 7.125V5.25ZM4.875 6H6v10.125A3.375 3.375 0 0 0 9.375 19.5H16.5v1.125c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V7.875C3 6.839 3.84 6 4.875 6Z" />
                </svg>

                <span class="mx-2 text-sm font-medium">Transaksi</span>
            </x-slot>
            <x-slot name="content">
                <x-side-dropdown-link :href="route('transaction.create')" :active="request()->routeIs('transaction.create')">
                    Transaksi
                </x-side-dropdown-link>
                <x-side-dropdown-link :href="route('transaction.history')" :active="request()->routeIs('transaction.history') || request()->routeIs('transaction.detail')">
                    Riwayat Transaksi
                </x-side-dropdown-link>
                @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                    <x-side-dropdown-link :href="route('debt.index')" :active="request()->routeIs('debt.*')">
                        Hutang
                    </x-side-dropdown-link>
                @endif
            </x-slot>
        </x-side-dropdown>

        <x-nav-link :href="route('goods.data')" :active="request()->routeIs('goods.*')">
            <x-slot name="trigger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                        d="M1.5 9.832v1.793c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875V9.832a3 3 0 0 0-.722-1.952l-3.285-3.832A3 3 0 0 0 16.215 3h-8.43a3 3 0 0 0-2.278 1.048L2.222 7.88A3 3 0 0 0 1.5 9.832ZM7.785 4.5a1.5 1.5 0 0 0-1.139.524L3.881 8.25h3.165a3 3 0 0 1 2.496 1.336l.164.246a1.5 1.5 0 0 0 1.248.668h2.092a1.5 1.5 0 0 0 1.248-.668l.164-.246a3 3 0 0 1 2.496-1.336h3.165l-2.765-3.226a1.5 1.5 0 0 0-1.139-.524h-8.43Z"
                        clip-rule="evenodd" />
                    <path
                        d="M2.813 15c-.725 0-1.313.588-1.313 1.313V18a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-1.688c0-.724-.588-1.312-1.313-1.312h-4.233a3 3 0 0 0-2.496 1.336l-.164.246a1.5 1.5 0 0 1-1.248.668h-2.092a1.5 1.5 0 0 1-1.248-.668l-.164-.246A3 3 0 0 0 7.046 15H2.812Z" />
                </svg>

                <span class="mx-2 text-sm font-medium">Data Barang</span>

        </x-nav-link>
    @endif

    @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
        <x-nav-link :href="route('order.index')" :active="request()->routeIs('order.*')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd"
                    d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
                    clip-rule="evenodd" />
            </svg>

            <span class="mx-2 text-sm font-medium">Data Order</span>
        </x-nav-link>
    @endif

    @if (auth()->user()->role === 'super_admin')
        <x-side-dropdown :active="request()->routeIs('report.*')">
            <x-slot name="trigger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                        d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="mx-2 text-sm font-medium">Laporan</span>
            </x-slot>
            <x-slot name="content">
                <x-side-dropdown-link :href="route('report.index')" :active="request()->routeIs('report.index')">
                    Laporan Penjualan
                </x-side-dropdown-link>
                <x-side-dropdown-link :href="route('report.goods')" :active="request()->routeIs('report.goods')">
                    Laporan Barang
                </x-side-dropdown-link>
            </x-slot>
        </x-side-dropdown>
    @endif
</nav>
