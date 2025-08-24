<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        {{-- Isi <head> ini sama persis dengan home.blade.php --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style> [x-cloak] { display: none !important; } </style>
    </head>
    <body class="antialiased">
        <div class="bg-slate-100 dark:bg-gray-900 parallax-bg">

            {{-- Memanggil Navigasi --}}
            @include('layouts.partials.public-nav')

            {{-- Konten Halaman Blog --}}
            <main class="pt-20">
                <section class="py-20">
                    <div class="max-w-4xl mx-auto px-6">
                        <div class="text-center mb-12">
                            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 dark:text-white">Artikel & Wawasan</h1>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Tips, tren, dan berita terbaru dari dunia kemasan.</p>
                        </div>

                        {{-- Daftar Artikel --}}
                        <div class="space-y-8">
                            {{-- Contoh Kartu Artikel - Nanti bisa di-loop dari database --}}
                            @for ($i = 0; $i < 4; $i++)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden md:flex">
                                <div class="md:w-1/3">
                                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?q=80&w=1974&auto=format&fit=crop" alt="Blog post image" class="h-48 w-full object-cover md:h-full">
                                </div>
                                <div class="p-6 md:w-2/3">
                                    <p class="text-sm text-blue-500 font-semibold">TIPS & TRIK</p>
                                    <h3 class="font-bold text-2xl text-gray-900 dark:text-white mt-2">5 Cara Membuat Desain Kemasan yang Menjual</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-xs text-gray-500">22 Agustus 2025</span>
                                        <a href="#" class="font-semibold text-blue-600 hover:underline">Baca Selengkapnya &rarr;</a>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </section>
            </main>

            {{-- Memanggil Footer --}}
            @include('layouts.partials.public-footer')
        </div>
    </body>
</html>