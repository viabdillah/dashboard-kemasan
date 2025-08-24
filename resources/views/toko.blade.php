{{-- Menggunakan layout yang sama dengan homepage --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        {{-- Isi <head> ini sama persis dengan home.blade.php --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Toko - {{ config('app.name', 'Laravel') }}</title>
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

            {{-- Konten Halaman Toko --}}
            <main class="pt-20">
                <section class="py-20">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="text-center mb-12">
                            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 dark:text-white">Katalog Produk Kami</h1>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Temukan kemasan yang sempurna untuk produk Anda.</p>
                        </div>

                        {{-- Filter dan Daftar Produk --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                            {{-- Contoh Kartu Produk - Nanti bisa di-loop dari database --}}
                            @for ($i = 0; $i < 8; $i++)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden group">
                                <img src="https://via.placeholder.com/400x300.png/007bff/ffffff?text=Produk+{{$i+1}}" alt="Produk" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="p-4">
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Standing Pouch #{{$i+1}}</h3>
                                    <p class="text-sm text-gray-500 mt-1">Ukuran 20x17 cm</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-xl font-bold text-blue-600">Rp 1.500</span>
                                        <a href="#" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full hover:bg-blue-200 transition">Lihat</a>
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