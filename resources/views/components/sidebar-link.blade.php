@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 mt-2 text-white bg-blue-600 rounded-md' // Kelas untuk link aktif
            : 'flex items-center px-4 py-2 mt-2 text-gray-600 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-200 hover:text-gray-700'; // Kelas untuk link normal
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>