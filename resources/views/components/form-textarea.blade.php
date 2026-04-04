
@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'rows' => 4,
    'value' => ''
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
    
    <textarea name="{{ $name }}" 
              id="{{ $id ?: $name }}"
              rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              {{ $required ? 'required' : '' }}
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                     focus:ring-2 focus:ring-green-500 focus:border-green-500 
                     block w-full p-2.5 transition-all duration-200 resize-none"
              {{ $attributes }}>{{ $value }}</textarea>
    <p class="text-sm text-red-600 hidden error-text" data-error-for="{{ $name }}"></p>
</div>