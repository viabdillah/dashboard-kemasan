<nav class="bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2">
                    <x-application-logo class="w-10 h-10 block fill-current text-blue-600" />
                    <span class="text-xl font-bold text-gray-800 dark:text-white">Pusat Layanan Kemasan</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-gray-600 dark:text-gray-300">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                <a href="{{ route('toko') }}" class="hover:text-blue-600 transition-colors">Toko</a>
                <a href="{{ route('blog') }}" class="hover:text-blue-600 transition-colors">Blog</a>
                <a href="/#services" class="hover:text-blue-600 transition-colors">Layanan Kami</a>
            </div>

            <div class="hidden md:flex items-center">
                <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 rounded-md hover:opacity-90 transition shadow">
                    SignIn
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 rounded-md hover:opacity-90 transition shadow">
                    SignUp
                </a>
            </div>

            <div class="md:hidden">
                <button class="text-gray-800 dark:text-white focus:outline-none">
                     <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
        </div>
    </div>
</nav>