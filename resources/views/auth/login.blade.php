<x-guest-layout>
    <div class="w-full max-w-5xl mx-auto" x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 100) }">
        <div x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <div class="text-white text-center md:text-left">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-white/80 inline-block md:inline" />
                </a>
                <h1 class="text-5xl lg:text-6xl font-bold mt-4 mb-4 leading-tight">
                    Dashboard Pusat Kemasan
                </h1>
                <p class="text-lg text-white/70">
                    Platform terintegrasi untuk mengelola pesanan, produksi, dan inventory Anda secara efisien.
                </p>
            </div>

            <div class="w-full p-8 bg-black/20 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl">
                <h2 class="text-3xl font-bold text-white mb-6 text-center">Login</h2>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Email" class="text-white/80" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="text-white/80"/>
                        <x-text-input id="password" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded bg-gray-900/50 border-gray-600 text-blue-500 shadow-sm focus:ring-blue-600" name="remember">
                            <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-400 hover:text-white" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full py-3 bg-blue-600 rounded-lg text-white font-bold uppercase hover:bg-blue-700 transition-colors duration-300 shadow-lg">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>