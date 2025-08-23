<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-plus-circle mr-2"></i>
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div x-data="{ 
                            variants: {{ json_encode(old('variants', [['name' => '', 'size' => '', 'sku' => '', 'quantity' => 0, 'unit' => 'pcs', 'low_stock_threshold' => 10]])) }}
                        }" class="p-6 md:p-8">
                    
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('operator.products.store') }}">
                        @csrf

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Produk Induk</h3>
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Nama Produk (Contoh: Kaos Polos, Stiker Vinyl)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        <hr class="my-6 dark:border-gray-700">

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Varian Produk</h3>
                            <x-input-error :messages="$errors->get('variants')" class="mt-2" />
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(variant, index) in variants" :key="index">
                                <div class="p-4 border rounded-md dark:border-gray-700 relative bg-gray-50 dark:bg-gray-800/50">
                                    <button type="button" @click="variants.splice(index, 1)" x-show="variants.length > 1" class="absolute -top-3 -right-3 w-7 h-7 bg-red-500 text-white rounded-full hover:bg-red-600 flex items-center justify-center text-xl font-bold leading-none pb-1">&times;</button>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label ::for="'variant_name_' + index" value="Nama Varian (Cth: Merah, L)" />
                                            <x-text-input ::id="'variant_name_' + index" class="block mt-1 w-full" type="text" x-model="variant.name" x-bind:name="'variants[' + index + '][name]'" required />
                                        </div>
                                        <div>
                                            <x-input-label ::for="'variant_sku_' + index" value="SKU (Opsional)" />
                                            <x-text-input ::id="'variant_sku_' + index" class="block mt-1 w-full" type="text" x-model="variant.sku" x-bind:name="'variants[' + index + '][sku]'" />
                                        </div>
                                        <div>
                                            <x-input-label ::for="'variant_size_' + index" value="Ukuran (Opsional)" />
                                            <x-text-input ::id="'variant_size_' + index" class="block mt-1 w-full" type="text" x-model="variant.size" x-bind:name="'variants[' + index + '][size]'" />
                                        </div>
                                        <div>
                                            <x-input-label ::for="'variant_quantity_' + index" value="Stok Awal" />
                                            <x-text-input ::id="'variant_quantity_' + index" class="block mt-1 w-full" type="number" x-model="variant.quantity" x-bind:name="'variants[' + index + '][quantity]'" required />
                                        </div>
                                         <div>
                                            <x-input-label ::for="'variant_unit_' + index" value="Satuan" />
                                            <x-text-input ::id="'variant_unit_' + index" class="block mt-1 w-full" type="text" x-model="variant.unit" x-bind:name="'variants[' + index + '][unit]'" required />
                                         </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                       <button type="button" 
                                @click="variants.push({ name: '', size: '', sku: '', quantity: 0, unit: 'pcs', low_stock_threshold: 10 })" 
                                class="mt-4 inline-flex items-center text-sm font-semibold text-green-600 dark:text-green-500 hover:text-green-800 dark:hover:text-green-400 transition-colors duration-200 ease-in-out">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Tambah Varian Lain</span>
                        </button>

                        <div class="flex items-center justify-end mt-8 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <x-primary-button>
                                {{ __('Simpan Produk dan Varian') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>