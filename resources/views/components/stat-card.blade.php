@props(['title', 'value', 'id', 'textColor' => 'text-gray-900'])

<div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
    <p class="text-sm text-gray-500">{{ $title }}</p>
    <p class="mt-1 text-2xl font-semibold {{ $textColor }}" id="{{$id}}">{{ $value }}</p>
</div>
