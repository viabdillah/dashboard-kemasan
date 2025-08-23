<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-edit mr-2"></i>
            {{ __('Edit Varian: ') }} {{ $variant->name }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form method="POST" action="{{ route('operator.variants.update', $variant->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Varian')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $variant->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sku" :value="__('SKU (Opsional)')" />
                                <x-text-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku', $variant->sku)" />
                                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="size" :value="__('Ukuran (Opsional)')" />
                                <x-text-input id="size" class="block mt-1 w-full" type="text" name="size" :value="old('size', $variant->size)" />
                                <x-input-error :messages="$errors->get('size')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="unit" :value="__('Satuan')" />
                                <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $variant->unit)" required />
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="low_stock_threshold" :value="__('Ambang Batas Stok Rendah')" />
                                <x-text-input id="low_stock_threshold" class="block mt-1 w-full" type="number" name="low_stock_threshold" :value="old('low_stock_threshold', $variant->low_stock_threshold)" required />
                                <x-input-error :messages="$errors->get('low_stock_threshold')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <a href="{{ route('operator.products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update Varian') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>