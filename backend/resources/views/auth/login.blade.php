<x-guest-layout>
    <x-slot name="title">{{ __('Login') }}</x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <input
                class="block mt-1 w-full border-sky-300 focus:border-sky-300 focus:ring-sky-500 rounded-md bg-transparent"
                type="text" name="username" id="username" :value="old('username')" required autofocus
                autocomplete="username">
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <input type="password"
                class="block mt-1 w-full border-sky-300 focus:border-sky-300 focus:ring-sky-500 rounded-md bg-transparent"
                id="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Login Type Selector -->
        <div class="mt-4">
            <x-input-label for="login_type" :value="__('Login As')" />
            <select id="login_type" name="login_type"
                class="block mt-1 w-full border-sky-300 focus:border-sky-300 focus:ring-sky-500 rounded-md bg-transparent">
                <option value="user" {{ old('login_type') === 'user' ? 'selected' : '' }}>User</option>
                <option value="customer" {{ old('login_type') === 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
            <x-input-error :messages="$errors->get('login_type')" class="mt-2" />
        </div>

        <!-- Login Button -->
        <x-primary-button class="w-full mt-6 bg-sky-500 hover:bg-sky-700 justify-center">
            <span class="tracking-normal normal-case">Login now</span>
        </x-primary-button>
    </form>
</x-guest-layout>
