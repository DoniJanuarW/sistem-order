@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'required' => false,
    'icon' => null,
    'options' => [], // ['value' => 'label']
    'selected' => '',
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
            <option value="" disabled {{ !$selected ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif
            
            @if($slot->isEmpty())
                @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
    </div>
    <p class="text-sm text-red-600 hidden error-text" data-error-for="{{ $name }}"></p>
</div>

