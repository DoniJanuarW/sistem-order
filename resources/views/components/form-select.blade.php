@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'required' => false,
    'icon' => null,
    'options' => [], // ['value' => 'label']
    'value' => '', // <-- Ubah 'selected' menjadi 'value'
    'placeholder' => 'Pilih opsi'
])

<div class="space-y-2">
    @if($label)
    <label for="{{ $id ?: $name }}" class="block text-sm font-semibold text-gray-900">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <div class="relative">
        @if($icon)
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="{{ $icon }} text-gray-400"></i>
        </div>
        @endif
        
        <select name="{{ $name }}" 
                id="{{ $id ?: $name }}"
                {{ $required ? 'required' : '' }}
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                       focus:ring-2 focus:ring-green-500 focus:border-green-500 
                       block w-full {{ $icon ? 'pl-10' : 'pl-3' }} pr-10 py-2.5
                       transition-all duration-200"
                {{ $attributes }}>
            @if($placeholder)
            <option value="" disabled {{ !$value ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif
            
            @if($slot->isEmpty())
                {{-- Perhatikan perubahan variabel $value loop di bawah ini agar tidak bentrok dengan $value dari props --}}
                @foreach($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" {{ $value == $optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
    </div>
    <p class="text-sm text-red-600 hidden error-text" data-error-for="{{ $name }}"></p>
</div>