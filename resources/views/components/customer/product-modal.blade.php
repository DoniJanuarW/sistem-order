<div id="productModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    
    <div onclick="closeProductModal()" 
         class="absolute inset-0 bg-black/60 backdrop-blur-[2px] transition-opacity duration-300 ease-in-out opacity-0" 
         id="modalBackdrop">
    </div>

    <div class="fixed inset-0 flex items-end justify-center pointer-events-none">
        <div class="relative w-full max-w-md bg-white rounded-t-[2rem] shadow-2xl transform transition-all duration-300 ease-out flex flex-col max-h-[90vh] translate-y-full pointer-events-auto" id="modalPanel">
            
            <div class="w-full flex justify-center pt-3 pb-2 cursor-pointer" onclick="closeProductModal()">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <div class="overflow-y-auto flex-1 pb-24 scrollbar-hide">
                
                <div class="relative h-64 w-full bg-gray-100 mb-4 overflow-hidden rounded-b-[2rem]">
                    <div id="modalImageDisplay" class="absolute inset-0 flex items-center justify-center text-8xl"></div>
                </div>

                <div class="px-6">
                    <span id="modalCategory" class="inline-block px-3 py-1 bg-green-100 text-[#014421] text-[10px] font-bold uppercase tracking-wider rounded-full mb-2">
                        COFFEE
                    </span>

                    <div class="flex justify-between items-start mb-2">
                        <h2 id="modalTitle" class="text-xl font-bold text-gray-800 w-2/3 leading-tight">Nama Produk</h2>
                        <div class="text-right w-1/3">
                            <p id="modalPrice" class="text-lg font-bold text-[#014421]">Rp 0</p>
                            <p id="modalOriginalPrice" class="text-[10px] text-gray-400 line-through hidden">Rp 0</p>
                        </div>
                    </div>

                    <p id="modalDescription" class="text-sm text-gray-500 leading-relaxed mb-5">
                        Deskripsi produk akan muncul di sini...
                    </p>

                    <hr class="border-gray-100 mb-4">

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Catatan Pesanan</label>
                        <textarea 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all resize-none"
                            rows="2"
                            placeholder="Contoh: Jangan terlalu manis..."
                            id="orderNotes"
                        ></textarea>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <div class="flex items-center gap-2">
                    
                    <div class="flex items-center bg-gray-100 rounded-full px-1 py-1">
                        <button onclick="updateQty(-1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-full shadow-sm text-gray-600 active:scale-90 transition-transform">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </button>
                        <span id="qtyDisplay" class="w-6 text-center font-bold text-gray-800 text-xs">1</span>
                        <button onclick="updateQty(1)" class="w-7 h-7 flex items-center justify-center !bg-[#014421] rounded-full shadow-sm text-white active:scale-90 transition-transform">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <button class="flex-1 !bg-[#014421] text-white py-2.5 px-3 rounded-full font-bold shadow-lg shadow-green-900/20 active:scale-95 transition-all flex justify-between items-center text-xs" id="buttonAddToCart">
                        <span>Tambah</span>
                        <span id="btnTotalPrice" class="bg-white/20 px-1.5 py-0.5 rounded text-[10px]">Rp 0</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
