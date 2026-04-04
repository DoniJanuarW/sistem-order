@php
    $variants = [
        'primary' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'danger'  => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
    ];

    $btnClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button
    id="{{ $id }}"
    type="{{ $type }}"
    class="relative inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl
           text-white font-semibold transition
           disabled:opacity-70 disabled:cursor-not-allowed
           focus:outline-none focus:ring-2 focus:ring-offset-2
           {{ $btnClass }}"
>

    <!-- Spinner -->
    <svg class="btn-spinner hidden animate-spin w-5 h-5 text-white"
         viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25"
                cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4" />
        <path class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
    </svg>

    <!-- Text -->
    <span class="btn-text">
        {{ $text }}
    </span>

</button>
