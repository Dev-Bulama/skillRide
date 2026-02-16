<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-2xl mb-4">
            <span class="text-white font-bold text-2xl">SR</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Welcome to SkillRide</h2>
        <p class="text-gray-500 text-sm mt-1">Fleet Management Platform</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg text-sm">
        <div class="flex items-start">
            <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('info') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                {{ __('Sign In') }}
            </button>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-700" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
            <a class="text-sm text-blue-600 hover:text-blue-700" href="{{ route('register') }}">
                {{ __('Register as Rider') }}
            </a>
        </div>
    </form>
</x-guest-layout>
