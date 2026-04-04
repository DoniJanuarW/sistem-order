@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'size' => 'md', // sm, md, lg, xl, 2xl, full
    'submitText' => 'Simpan',
    'cancelText' => 'Batal',
    'formId' => null,
    'submitColor' => 'green', // green, blue, red, purple
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-2xl',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
        '2xl' => 'max-w-7xl',
        'full' => 'max-w-full mx-4'
    ];
    
    $colors = [
        'green' => 'bg-green-700 hover:bg-green-800 focus:ring-green-300',
        'blue' => 'bg-blue-700 hover:bg-blue-800 focus:ring-blue-300',
        'red' => 'bg-red-700 hover:bg-red-800 focus:ring-red-300',
        'purple' => 'bg-purple-700 hover:bg-purple-800 focus:ring-purple-300',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$submitColor] ?? $colors['green'];
@endphp

<div id="{{ $id }}" 
     tabindex="-1" 
     aria-hidden="true" 
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen bg-black/50 backdrop-blur-sm">
    <div class="relative p-4 w-full {{ $sizeClass }} max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-200 rounded-t bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-{{ $submitColor }}-100 rounded-lg">
                        <i class="ti ti-forms text-{{ $submitColor }}-700 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900" id="{{ $id }}-title">
                            {{ $title }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5" id="{{ $id }}-subtitle">
                            Lengkapi form di bawah ini
                        </p>
                    </div>
                </div>
                <button type="button" 
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-all"
                        data-modal-hide="{{ $id }}">
                    <i class="ti ti-x text-xl"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <!-- Modal body -->
            <div class="p-6 space-y-6 max-h-[calc(100vh-250px)] overflow-y-auto">
                {{ $slot }}
            </div>
            
            <!-- Modal footer -->
            <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200 rounded-b bg-gray-50">
                <button type="button" 
                        data-modal-hide="{{ $id }}"
                        class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 transition-all">
                    <i class="ti ti-x mr-1"></i>
                    {{ $cancelText }}
                </button>
                <button type="submit" 
                        @if($formId) form="{{ $formId }}" @endif
                        class="text-white {{ $colorClass }} focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-md hover:shadow-lg">
                    <i class="ti ti-check mr-1"></i>
                    {{ $submitText }}
                </button>
            </div>
        </div>
    </div>
</div>
