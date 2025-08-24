<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pusat Layanan Kemasan - Solusi Cetak Anda</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style> [x-cloak] { display: none !important; } </style>
    </head>
    <body class="antialiased">
        <div class="bg-slate-100 dark:bg-gray-900 parallax-bg">

            @include('layouts.partials.public-nav')

            <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
                <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                    <div class="text-center md:text-left z-10" 
                         x-data="{ scrollY: 0 }" 
                         @scroll.window="scrollY = window.pageYOffset"
                         :style="{ transform: `translateY(${scrollY * 0.15}px)` }">

                        <span class="text-blue-600 font-semibold uppercase tracking-wider">Solusi Kemasan Terpadu</span>
                        <h1 class="text-4xl md:text-6xl font-extrabold text-gray-800 dark:text-white my-4 leading-tight">
                            Desain & Cetak Kemasan Profesional
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                            Wujudkan identitas brand Anda dengan kemasan berkualitas tinggi yang dirancang untuk menarik perhatian.
                        </p>
                        <div class="flex justify-center md:justify-start gap-x-4">
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-blue-600 rounded-md text-white font-bold hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">Mulai Proyek</a>
                            <a href="#services" class="px-8 py-3 bg-gray-200 rounded-md text-gray-800 font-bold hover:bg-gray-300 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">Lihat Layanan</a>
                        </div>
                    </div>

                    <div class="flex justify-center" x-data="{ scrollY: 0 }" @scroll.window="scrollY = window.pageYOffset" :style="{ transform: `translateY(${scrollY * -0.1}px)` }">
                        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop" alt="Desain kemasan modern" class="rounded-2xl shadow-2xl max-w-sm md:max-w-full">
                    </div>
                </div>
            </section>

            <section id="services" class="min-h-screen flex items-center bg-white dark:bg-gray-800">
                 <div class="max-w-7xl mx-auto px-6 py-20" x-data="animateOnScroll">
                     <div x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <div class="text-center mb-12">
                            <h2 class="text-4xl font-extrabold text-gray-800 dark:text-white">Layanan Unggulan Kami</h2>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Dari ide hingga produk jadi, kami siap membantu.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-slate-50 dark:bg-gray-700/50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300"><i class="fas fa-palette text-4xl text-blue-500 mb-4"></i><h3 class="text-2xl font-bold text-gray-900 dark:text-white">Desain Grafis</h3><p class="mt-2 text-gray-600 dark:text-gray-400">Tim desainer kami siap membuat visual kemasan yang menarik dan sesuai dengan brand Anda.</p></div>
                            <div class="bg-slate-50 dark:bg-gray-700/50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300"><i class="fas fa-print text-4xl text-yellow-500 mb-4"></i><h3 class="text-2xl font-bold text-gray-900 dark:text-white">Cetak Kemasan</h3><p class="mt-2 text-gray-600 dark:text-gray-400">Produksi cetak berkualitas tinggi dengan berbagai pilihan bahan.</p></div>
                            <div class="bg-slate-50 dark:bg-gray-700/50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300"><i class="fas fa-boxes-packing text-4xl text-green-500 mb-4"></i><h3 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Stok</h3><p class="mt-2 text-gray-600 dark:text-gray-400">Pantau stok bahan baku dan produk jadi Anda dengan mudah.</p></div>
                        </div>
                    </div>
                </div>
            </section>

            @include('layouts.partials.public-footer')
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('animateOnScroll', () => ({
                    show: false,
                    init() {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    this.show = true;
                                    observer.unobserve(this.$el);
                                }
                            });
                        }, { threshold: 0.1 });
                        observer.observe(this.$el);
                    }
                }));
            });
        </script>
    </body>
</html>