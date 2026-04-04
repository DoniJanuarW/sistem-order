/**
 * Walk-in Order Management System
 * Handles menu display, cart management, and order processing
 */

class WalkinOrderSystem {
    constructor() {
        this.state = {
            menuItems: [],
            cart: [],
            currentCategory: 'all',
            loading: false,
            selectedPaymentMethod: '',
            grandTotalAmount: 0,
            selectedTable: null
        };

        this.elements = this.cacheElements();
        this.routes = this.getRoutes();
        this.init();
    }

    /**
     * Cache DOM elements for better performance
     */
    cacheElements() {
        return {
            loading: document.getElementById('loadingState'),
            container: document.getElementById('menuContainer'),
            cartContainer: document.getElementById('cartContainer'),
            tableGrid: document.getElementById('tableGrid'),
            customerName: document.getElementById('customerName'),
            tableNo: document.getElementById('tableNo'),
            totalPrice: document.getElementById('totalPrice'),
            finalTotal: document.getElementById('finalTotal'),
            totalPayment: document.getElementById('totalPayment'),
            paymentModal: document.getElementById('paymentModal'),
            cashAmount: document.getElementById('cashAmount'),
            changeDisplay: document.getElementById('changeDisplay'),
        };
    }

    /**
     * Get Laravel routes from meta tags or data attributes
     * Usage: Add to your blade template:
     * <meta name="route-table-all" content="{{ route('cashier.table.all') }}">
     * <meta name="route-menu-all" content="{{ route('cashier.menu.all') }}">
     */
    getRoutes() {
        return {
            tableAll: document.querySelector('meta[name="route-table-all"]')?.content || '/api/tables',
            menuAll: document.querySelector('meta[name="route-menu-all"]')?.content || '/api/menu',
        };
    }

    /**
     * Initialize the application
     */
    init() {
        this.fetchMenu();
        this.fetchTables();
        this.setupEventListeners();
    }

    /**
     * Setup global event listeners
     */
    setupEventListeners() {
        // Category filter buttons
        document.querySelectorAll('[data-category]')?.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.target.dataset.category;
                this.filterByCategory(category);
            });
        });

        // Cash amount input for change calculation
        this.elements.cashAmount?.addEventListener('input', () => {
            this.calculateChange();
        });

        // Global event delegation for all action buttons
        document.addEventListener('click', (e) => {
            const target = e.target.closest('[data-action]');
            if (!target) return;

            const action = target.dataset.action;
            
            switch(action) {
                case 'open-payment-modal':
                    e.preventDefault();
                    this.openPaymentModal();
                    break;
                case 'close-payment-modal':
                    e.preventDefault();
                    this.closePaymentModal();
                    break;
                case 'process-payment':
                    e.preventDefault();
                    this.processPayment();
                    break;
                case 'set-payment-method':
                    e.preventDefault();
                    const method = target.dataset.paymentMethod;
                    if (method) this.setPaymentMethod(method);
                    break;
            }
        });
    }

    /**
     * Set loading state
     * @param {boolean} isLoading - Loading state
     * @param {boolean} showSkeleton - Whether to show skeleton loader
     */
    setLoading(isLoading, showSkeleton = true) {
        this.state.loading = isLoading;
        
        if (!showSkeleton || !this.elements.loading) return;

        this.elements.loading.classList.toggle('hidden', !isLoading);
        this.elements.container.classList.toggle('hidden', isLoading);
    }

    /**
     * Fetch tables from API
     */
    async fetchTables() {
        try {
            const response = await fetch(this.routes.tableAll);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            const tables = data.data ?? data;
            
            this.renderTableGrid(tables);
        } catch (error) {
            console.error('Error fetching tables:', error);
            this.showTableError();
        }
    }

    /**
     * Show error message for table loading failure
     */
    showTableError() {
        if (this.elements.tableGrid) {
            this.elements.tableGrid.innerHTML = 
                '<p class="col-span-4 text-red-500 text-sm text-center">Gagal memuat daftar meja. Silakan refresh halaman.</p>';
        }
    }

    /**
     * Render table grid
     * @param {Array} tables - Array of table objects
     */
    renderTableGrid(tables) {
        if (!this.elements.tableGrid) return;

        this.elements.tableGrid.innerHTML = '';

        tables.forEach(table => {
            const tableButton = this.createTableButton(table);
            this.elements.tableGrid.appendChild(tableButton);
        });
    }

    /**
     * Create table button element
     * @param {Object} table - Table object
     * @returns {HTMLButtonElement}
     */
    createTableButton(table) {
        const isAvailable = table.status === 'active';
        const btn = document.createElement('button');
        
        btn.type = 'button';
        btn.textContent = table.table_number;
        btn.dataset.tableId = table.id;
        btn.className = this.getTableButtonClasses(isAvailable);
        btn.disabled = !isAvailable;

        if (isAvailable) {
            btn.addEventListener('click', () => {
                this.selectTable(table.table_number, btn);
            });
        }

        return btn;
    }

    /**
     * Get CSS classes for table button
     * @param {boolean} isAvailable - Whether table is available
     * @returns {string}
     */
    getTableButtonClasses(isAvailable) {
        const baseClasses = 'border rounded-lg py-2 px-1 text-sm font-semibold transition duration-200';
        
        if (isAvailable) {
            return `${baseClasses} border-gray-300 bg-white hover:border-blue-500 hover:text-blue-600 text-gray-700`;
        }
        
        return `${baseClasses} border-red-100 bg-red-50 text-red-300 cursor-not-allowed`;
    }

    /**
     * Select a table
     * @param {string} tableName - Table number/name
     * @param {HTMLButtonElement} selectedBtn - The clicked button
     */
    selectTable(tableName, selectedBtn) {
        // Remove selection from all buttons
        this.elements.tableGrid.querySelectorAll('button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        });

        // Add selection to clicked button
        selectedBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
        selectedBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

        // Update state and input
        this.state.selectedTable = tableName;
        
        if (this.elements.tableNo) {
            this.elements.tableNo.value = tableName;
        }
    }

    /**
     * Fetch menu items from API
     */
    async fetchMenu() {
        if (this.state.loading) return;

        this.setLoading(true);

        try {
            const response = await fetch(this.routes.menuAll);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.state.menuItems = data.data ?? data;
            
            this.renderMenu();
        } catch (error) {
            console.error('Error fetching menu:', error);
            this.showMenuError();
        } finally {
            this.setLoading(false);
        }
    }

    /**
     * Show error message for menu loading failure
     */
    showMenuError() {
        if (this.elements.container) {
            this.elements.container.innerHTML = 
                '<p class="text-center text-red-500 w-full py-10">Gagal memuat menu. Silakan refresh halaman.</p>';
        }
    }

    /**
     * Filter menu by category
     * @param {string} category - Category name
     */
    filterByCategory(category) {
        this.state.currentCategory = category;
        this.renderMenu();
    }

    /**
     * Get filtered menu items based on current category
     * @returns {Array}
     */
    getFilteredMenuItems() {
        if (this.state.currentCategory === 'all') {
            return this.state.menuItems;
        }

        return this.state.menuItems.filter(item => 
            item.category.name.toLowerCase() === this.state.currentCategory
        );
    }

    /**
     * Render menu items
     */
    renderMenu() {
        if (!this.elements.container) return;

        const items = this.getFilteredMenuItems();

        if (items.length === 0) {
            this.elements.container.innerHTML = 
                '<p class="text-center text-gray-500 w-full py-10">Tidak ada menu tersedia.</p>';
            return;
        }

        this.elements.container.innerHTML = items
            .map(item => this.createMenuItemHTML(item))
            .join('');

        // Attach event listeners to menu items
        this.attachMenuItemListeners();
    }

    /**
     * Create HTML for menu item
     * @param {Object} item - Menu item object
     * @returns {string}
     */
    createMenuItemHTML(item) {
        const formattedPrice = this.formatCurrency(item.price);
        
        return `
            <div class="menu-item bg-white border-2 border-gray-200 rounded-lg p-4 cursor-pointer flex flex-col hover:border-blue-400 transition-colors" 
                 data-menu-id="${item.id}">
                <div class="flex-1 flex flex-col items-center">
                    <img src="${item.image_url}" 
                         alt="${item.name}"
                         class="w-24 h-24 rounded-full mb-3 object-cover">
                    <h3 class="font-semibold text-center h-12 line-clamp-2">${item.name}</h3>
                    <p class="text-blue-600 font-bold">Rp ${formattedPrice}</p>
                </div>
                <button type="button" 
                        class="w-full bg-blue-600 text-white py-2 rounded-lg mt-3 hover:bg-blue-700 transition-colors"
                        data-add-to-cart="${item.id}">
                    + Tambah
                </button>
            </div>
        `;
    }

    /**
     * Attach event listeners to menu items
     */
    attachMenuItemListeners() {
        this.elements.container.querySelectorAll('[data-add-to-cart]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const itemId = parseInt(btn.dataset.addToCart);
                this.addToCart(itemId);
            });
        });
    }

    /**
     * Add item to cart
     * @param {number} itemId - Menu item ID
     */
    addToCart(itemId) {
        const item = this.state.menuItems.find(m => m.id === itemId);
        
        if (!item) {
            console.error(`Menu item with ID ${itemId} not found`);
            return;
        }

        const existingItem = this.state.cart.find(c => c.id === itemId);

        if (existingItem) {
            existingItem.qty++;
        } else {
            this.state.cart.push({
                ...item,
                qty: 1
            });
        }

        this.renderCart();
    }

    /**
     * Update item quantity in cart
     * @param {number} itemId - Menu item ID
     * @param {number} change - Quantity change (+1 or -1)
     */
    updateQuantity(itemId, change) {
        const item = this.state.cart.find(c => c.id === itemId);
        
        if (!item) return;

        item.qty += change;

        if (item.qty <= 0) {
            this.removeFromCart(itemId);
        } else {
            this.renderCart();
        }
    }

    /**
     * Remove item from cart
     * @param {number} itemId - Menu item ID
     */
    removeFromCart(itemId) {
        this.state.cart = this.state.cart.filter(c => c.id !== itemId);
        this.renderCart();
    }

    /**
     * Clear entire cart
     */
    clearCart() {
        this.state.cart = [];
        this.renderCart();
    }

    /**
     * Render cart items
     */
    renderCart() {
        if (!this.elements.cartContainer) return;

        if (this.state.cart.length === 0) {
            this.elements.cartContainer.innerHTML = 
                '<p class="text-gray-500 text-center py-4">Belum ada item dalam keranjang</p>';
            this.updateTotal();
            return;
        }

        this.elements.cartContainer.innerHTML = this.state.cart
            .map(item => this.createCartItemHTML(item))
            .join('');

        this.attachCartItemListeners();
        this.updateTotal();
    }

    /**
     * Create HTML for cart item
     * @param {Object} item - Cart item object
     * @returns {string}
     */
    createCartItemHTML(item) {
        const itemTotal = item.price * item.qty;
        const formattedPrice = this.formatCurrency(item.price);
        const formattedTotal = this.formatCurrency(itemTotal);

        return `
            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-sm flex-1">${item.name}</h4>
                    <button type="button" 
                            class="text-red-500 hover:text-red-700 ml-2"
                            data-remove-item="${item.id}"
                            title="Hapus item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-600 mb-2">Rp ${formattedPrice} / item</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-7 h-7 rounded flex items-center justify-center"
                                data-decrease-qty="${item.id}">
                            −
                        </button>
                        <span class="font-semibold w-8 text-center">${item.qty}</span>
                        <button type="button" 
                                class="bg-blue-600 hover:bg-blue-700 text-white w-7 h-7 rounded flex items-center justify-center"
                                data-increase-qty="${item.id}">
                            +
                        </button>
                    </div>
                    <span class="font-bold text-blue-600">Rp ${formattedTotal}</span>
                </div>
            </div>
        `;
    }

    /**
     * Attach event listeners to cart items
     */
    attachCartItemListeners() {
        // Decrease quantity buttons
        this.elements.cartContainer.querySelectorAll('[data-decrease-qty]').forEach(btn => {
            btn.addEventListener('click', () => {
                const itemId = parseInt(btn.dataset.decreaseQty);
                this.updateQuantity(itemId, -1);
            });
        });

        // Increase quantity buttons
        this.elements.cartContainer.querySelectorAll('[data-increase-qty]').forEach(btn => {
            btn.addEventListener('click', () => {
                const itemId = parseInt(btn.dataset.increaseQty);
                this.updateQuantity(itemId, 1);
            });
        });

        // Remove item buttons
        this.elements.cartContainer.querySelectorAll('[data-remove-item]').forEach(btn => {
            btn.addEventListener('click', () => {
                const itemId = parseInt(btn.dataset.removeItem);
                this.removeFromCart(itemId);
            });
        });
    }

    /**
     * Calculate and update total price
     */
    updateTotal() {
        const total = this.calculateTotal();
        const formattedTotal = this.formatCurrency(total);

        if (this.elements.totalPrice) {
            this.elements.totalPrice.textContent = formattedTotal;
        }

        if (this.elements.finalTotal) {
            this.elements.finalTotal.textContent = formattedTotal;
        }

        if (this.elements.totalPayment) {
            this.elements.totalPayment.textContent = formattedTotal;
        }

        this.state.grandTotalAmount = total;
    }

    /**
     * Calculate total cart value
     * @returns {number}
     */
    calculateTotal() {
        return this.state.cart.reduce((sum, item) => {
            return sum + (item.price * item.qty);
        }, 0);
    }

    /**
     * Calculate change amount
     */
    calculateChange() {
        if (!this.elements.cashAmount || !this.elements.changeDisplay) return;

        const cashAmount = parseFloat(this.elements.cashAmount.value) || 0;
        const change = cashAmount - this.state.grandTotalAmount;

        if (change >= 0) {
            this.elements.changeDisplay.textContent = this.formatCurrency(change);
            this.elements.changeDisplay.classList.remove('text-red-500');
            this.elements.changeDisplay.classList.add('text-green-600');
        } else {
            this.elements.changeDisplay.textContent = 'Uang kurang';
            this.elements.changeDisplay.classList.remove('text-green-600');
            this.elements.changeDisplay.classList.add('text-red-500');
        }
    }

    /**
     * Format number as Indonesian Rupiah
     * @param {number} amount - Amount to format
     * @returns {string}
     */
    formatCurrency(amount) {
        return amount.toLocaleString('id-ID');
    }

    /**
     * Open payment modal
     */
    openPaymentModal() {
        const validation = this.validateOrder();
        
        if (!validation.valid) {
            this.showValidationErrors(validation.errors);
            return;
        }

        if (this.elements.paymentModal) {
            this.elements.paymentModal.classList.remove('hidden');
            this.elements.paymentModal.classList.add('flex');
            
            // Reset payment inputs
            if (this.elements.cashAmount) {
                this.elements.cashAmount.value = '';
            }
            if (this.elements.changeDisplay) {
                this.elements.changeDisplay.textContent = 'Rp 0';
            }
        }
    }

    /**
     * Close payment modal
     */
    closePaymentModal() {
        if (this.elements.paymentModal) {
            this.elements.paymentModal.classList.add('hidden');
            this.elements.paymentModal.classList.remove('flex');
        }
        
        // Reset payment method selection
        this.state.selectedPaymentMethod = '';
    }

    /**
     * Set payment method
     * @param {string} method - Payment method (cash, qris, debit, etc)
     */
    setPaymentMethod(method) {
        this.state.selectedPaymentMethod = method;
        
        // Update UI to show selected payment method
        document.querySelectorAll('[data-payment-method]').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50');
        });
        
        const selectedBtn = document.querySelector(`[data-payment-method="${method}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50');
        }

        // Show/hide cash input based on payment method
        const cashInputSection = document.getElementById('cashInputSection');
        if (cashInputSection) {
            cashInputSection.classList.toggle('hidden', method !== 'cash');
        }
    }

    /**
     * Process payment and submit order
     */
    async processPayment() {
        const validation = this.validateOrder();
        
        if (!validation.valid) {
            this.showValidationErrors(validation.errors);
            return;
        }

        if (!this.state.selectedPaymentMethod) {
            this.showAlert('Pilih metode pembayaran terlebih dahulu', 'error');
            return;
        }

        // For cash payment, validate cash amount
        if (this.state.selectedPaymentMethod === 'cash') {
            const cashAmount = parseFloat(this.elements.cashAmount?.value) || 0;
            if (cashAmount < this.state.grandTotalAmount) {
                this.showAlert('Jumlah uang tunai tidak mencukupi', 'error');
                return;
            }
        }

        try {
            const orderData = this.getOrderData();
            
            // Show loading state
            this.setLoadingButton('processPaymentBtn', true, 'Memproses...');
            
            // Submit order to backend
            const response = await this.submitOrder(orderData);
            
            if (response.ok) {
                const result = await response.json();
                this.showAlert('Pesanan berhasil diproses!', 'success');
                this.closePaymentModal();
                this.resetOrder();
                
                // Optional: redirect or print receipt
                if (result.redirect) {
                    window.location.href = result.redirect;
                }
            } else {
                throw new Error('Gagal memproses pesanan');
            }
        } catch (error) {
            console.error('Payment processing error:', error);
            this.showAlert('Terjadi kesalahan saat memproses pembayaran', 'error');
        } finally {
            this.setLoadingButton('processPaymentBtn', false, 'Proses Bayar');
        }
    }

    /**
     * Submit order to backend
     * @param {Object} orderData - Order data to submit
     * @returns {Promise<Response>}
     */
    async submitOrder(orderData) {
        const submitUrl = document.querySelector('meta[name="route-order-submit"]')?.content || '/api/orders';
        
        return fetch(submitUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
        });
    }

    /**
     * Reset order after successful payment
     */
    resetOrder() {
        this.clearCart();
        
        if (this.elements.customerName) {
            this.elements.customerName.value = '';
        }
        
        this.state.selectedTable = null;
        this.state.selectedPaymentMethod = '';
        
        // Reset table selection UI
        document.querySelectorAll('#tableGrid button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        });
        
        // Refresh tables to update availability
        this.fetchTables();
    }

    /**
     * Show validation errors
     * @param {Array} errors - Array of error messages
     */
    showValidationErrors(errors) {
        const errorMessage = errors.join('\n');
        this.showAlert(errorMessage, 'error');
    }

    /**
     * Show alert/notification to user
     * @param {string} message - Alert message
     * @param {string} type - Alert type (success, error, warning, info)
     */
    showAlert(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            console.log(`[${type}] ${message}`);
            alert(message); 
        }
    }

    /**
     * Set loading state for button
     * @param {string} buttonId - Button ID
     * @param {boolean} isLoading - Loading state
     * @param {string} text - Button text
     */
    setLoadingButton(buttonId, isLoading, text) {
        const button = document.getElementById(buttonId);
        if (!button) return;

        button.disabled = isLoading;
        button.textContent = text;
        
        if (isLoading) {
            button.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    /**
     * Get current cart data for form submission
     * @returns {Object}
     */
    getOrderData() {
        const cashAmount = this.state.selectedPaymentMethod === 'cash' 
            ? parseFloat(this.elements.cashAmount?.value) || 0 
            : null;

        return {
            customer_name: this.elements.customerName?.value || '',
            table_number: this.state.selectedTable,
            items: this.state.cart.map(item => ({
                menu_id: item.id,
                quantity: item.qty
            })),
            total_amount: this.state.grandTotalAmount,
            payment_method: this.state.selectedPaymentMethod,
        };
    }



    /**
     * Validate order before submission
     * @returns {Object} - {valid: boolean, errors: Array}
     */
    validateOrder() {
        const errors = [];

        if (!this.elements.customerName?.value.trim()) {
            errors.push('Nama pelanggan harus diisi');
        }

        if (this.state.cart.length === 0) {
            errors.push('Keranjang masih kosong');
        }

        return {
            valid: errors.length === 0,
            errors
        };
    }
}

// Initialize when DOM is ready
let walkinSystem;

// Expose methods to global scope IMMEDIATELY for inline onclick handlers
// These are bridges between inline onclick and the class methods
window.openPaymentModal = () => {
    if (!walkinSystem) {
        console.error('WalkinSystem not initialized yet');
        return;
    }
    walkinSystem.openPaymentModal();
};

window.closePaymentModal = () => {
    if (!walkinSystem) return;
    walkinSystem.closePaymentModal();
};

window.processPayment = () => {
    if (!walkinSystem) return;
    walkinSystem.processPayment();
};

window.setPaymentMethod = (method) => {
    if (!walkinSystem) return;
    walkinSystem.setPaymentMethod(method);
};

// Initialize the system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    walkinSystem = new WalkinOrderSystem();
});

// Export for external access if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WalkinOrderSystem;
}