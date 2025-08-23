<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-plus-circle mr-2"></i>
            {{ __('Buat Pesanan Baru') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Detail Pesanan</h3>
                    <form method="POST" action="{{ route('cashier.orders.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="customer_name" :value="__('Nama Pembeli')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <x-text-input id="customer_name" class="block w-full ps-10" type="text" name="customer_name" :value="old('customer_name')" required autofocus placeholder="John Doe"/>
                                </div>
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="product_name" :value="__('Nama Produk')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-box-open text-gray-400"></i>
                                    </div>
                                    <x-text-input id="product_name" class="block w-full ps-10" type="text" name="product_name" :value="old('product_name')" required placeholder="Kemasan Kopi"/>
                                </div>
                                <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                            </div>

                            <div 
                                x-data="{ 
                                    packagingType: '{{ old('packaging_type', '') }}', 
                                    size: '{{ old('size', '') }}',
                                    customSize: false,
                                    get availableSizes() {
                                        if (this.packagingType === 'Standing Pouch') {
                                            return ['20x17', 'custom'];
                                        }
                                        if (this.packagingType === 'Stiker') {
                                            return ['5x5 cm', '7x7 cm', '10x10 cm', 'custom'];
                                        }
                                        // Tambahkan jenis kemasan lain di sini jika perlu
                                        return [];
                                    }
                                }"
                                x-init="$watch('packagingType', value => { size = ''; customSize = false; })"
                                class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6"
                            >
                                <div>
                                    <x-input-label for="packaging_type" :value="__('Jenis Kemasan')" />
                                    <select id="packaging_type" name="packaging_type" x-model="packagingType" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="" disabled>Pilih jenis kemasan</option>
                                        <option value="Standing Pouch">Standing Pouch</option>
                                        <option value="Stiker">Stiker</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="size" :value="__('Ukuran')" />
                                    <select id="size_dropdown" name="size_dropdown" x-show="availableSizes.length > 0 && !customSize" x-model="size" @change="if ($event.target.value === 'custom') { customSize = true; size = ''; }" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="" disabled>Pilih ukuran</option>
                                        <template x-for="s in availableSizes" :key="s">
                                            <option :value="s" x-text="s === 'custom' ? 'Ukuran Lain (Custom)' : s"></option>
                                        </template>
                                    </select>
                                    <input type="text" id="size_text" name="size_text" x-show="packagingType === 'Lainnya' || customSize" x-model="size" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Masukkan ukuran kustom">
                                    <input type="hidden" name="size" :value="size">
                                    <x-input-error :messages="$errors->get('size')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="net_weight" :value="__('Berat Bersih')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-weight-hanging text-gray-400"></i>
                                    </div>
                                    <x-text-input id="net_weight" class="block w-full ps-10" type="text" name="net_weight" :value="old('net_weight')" required placeholder="Contoh: 250 gram"/>
                                </div>
                                <x-input-error :messages="$errors->get('net_weight')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="packaging_label" :value="__('Label Kemasan')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <x-text-input id="packaging_label" class="block w-full ps-10" type="text" name="packaging_label" :value="old('packaging_label')" required placeholder="Kopi Arabica Gayo"/>
                                </div>
                                <x-input-error :messages="$errors->get('packaging_label')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="price_per_piece" :value="__('Harga Per Pieces (Rp)')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400"></i>
                                    </div>
                                    <x-text-input id="price_per_piece" class="block w-full ps-10" type="number" name="price_per_piece" :value="old('price_per_piece')" required placeholder="15000"/>
                                </div>
                                <x-input-error :messages="$errors->get('price_per_piece')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="quantity" :value="__('Jumlah Beli')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <i class="fas fa-sort-numeric-up text-gray-400"></i>
                                    </div>
                                    <x-text-input id="quantity" class="block w-full ps-10" type="number" name="quantity" :value="old('quantity')" required placeholder="100"/>
                                </div>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="pirt_number" :value="__('No. PIRT (Opsional)')" />
                                <x-text-input id="pirt_number" class="block mt-1 w-full" type="text" name="pirt_number" :value="old('pirt_number')" />
                                <x-input-error :messages="$errors->get('pirt_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="halal_number" :value="__('No. Halal (Opsional)')" />
                                <x-text-input id="halal_number" class="block mt-1 w-full" type="text" name="halal_number" :value="old('halal_number')" />
                                <x-input-error :messages="$errors->get('halal_number')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label :value="__('Status Desain')" class="mb-2"/>
                                <div class="flex gap-x-6">
                                    <div class="flex">
                                        <input type="radio" id="has_design_yes" name="has_design" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" checked>
                                        <label for="has_design_yes" class="text-sm text-gray-500 ms-2 dark:text-gray-400">Sudah Ada Desain</label>
                                    </div>
                                    <div class="flex">
                                        <input type="radio" id="has_design_no" name="has_design" value="0" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                        <label for="has_design_no" class="text-sm text-gray-500 ms-2 dark:text-gray-400">Belum Ada Desain</label>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('has_design')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <x-primary-button>
                                <i class="fas fa-save mr-2"></i>
                                {{ __('Simpan Pesanan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>