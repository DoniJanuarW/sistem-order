@props(['id' => 'datatables'])
<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table id="{{ $id }}"
                   class="w-full text-sm text-left text-gray-700">
                {{ $slot }}
            </table>
        </div>
    </div>
</div>
