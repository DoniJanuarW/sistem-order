
@props([
    'type' => 'text',
    'name' => '',
    'id' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'icon' => null,
    'readonly' => false,
    'disabled' => false
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
        
        <input type="{{ $type }}" 
               name="{{ $name }}" 
               id="{{ $id ?: $name }}" 
               value="{{ $value }}"
               placeholder="{{ $placeholder }}"
               {{ $required ? 'required' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               {{ $disabled ? 'disabled' : '' }}
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                      focus:ring-2 focus:ring-green-500 focus:border-green-500 
                      block w-full {{ $icon ? 'pl-10' : 'pl-3' }} pr-3 py-2.5
                      transition-all duration-200
                      {{ $readonly || $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
               {{ $attributes }}>
    </div>
    <p class="text-sm text-red-600 hidden error-text" data-error-for="{{ $name }}"></p>
</div>