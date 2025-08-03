<div>
    <x-slot name="title">{{ __('Transaksi Data Admin') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $data = ['Transaksi', 'Transaksi Data Admin'];
        @endphp
        @foreach ($data as $item)
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <span
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">
                    {{ $item }}
                </span>
            </li>
        @endforeach
    </x-slot>

    <div class="bg-gray-50 overflow-hidden shadow-lg sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __('Halaman Transaksi Data Admin') }}
        </div>
    </div>
</div>
