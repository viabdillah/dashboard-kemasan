<x-guest-layout>
    <div class="w-full max-w-5xl mx-auto" x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 100) }">
        <div x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <div class="text-white text-center md:text-left">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-white/80 inline-block md:inline" />
                </a>
                <h1 class="text-5xl lg:text-6xl font-bold mt-4 mb-4 leading-tight">
                    Buat Akun Baru
                </h1>
                <p class="text-lg text-white/70">
                    Bergabunglah dengan kami untuk mulai mengelola semua proses bisnis Anda dalam satu platform terintegrasi.
                </p>
            </div>

            <div class="w-full p-8 bg-black/20 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl">
                <h2 class="text-3xl font-bold text-white mb-6 text-center">Register</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nama" class="text-white/80" />
                        <x-text-input id="name" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" value="Email" class="text-white/80" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="text-white/80"/>
                        <x-text-input id="password" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-white/80"/>
                        <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white/10 border-white/20 text-white placeholder:text-gray-400 focus:border-blue-400 focus:ring-blue-400" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a class="underline text-sm text-gray-400 hover:text-white rounded-md focus:outline-none" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>

                        <x-primary-button class="ms-4">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>