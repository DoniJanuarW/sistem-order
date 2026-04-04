class CrudManager {
    constructor(config) {
        this.config = {
            entity: config.entity || 'item',                    // e.g., 'user', 'menu', 'table'
            tableId: config.tableId || '#datatables',           // DataTable selector
            modalId: config.modalId || 'form-modal',            // Modal ID
            formId: config.formId || 'main-form',               // Form ID
            apiUrl: config.apiUrl || `/${config.entity}`,       // API base URL
            dataTableUrl: config.dataTableUrl || `/${config.entity}/table`, // DataTable URL
            columns: config.columns || [],                      // DataTable columns
            formFields: config.formFields || [],                // Form field names
            onSuccess: config.onSuccess || null,                // Success callback
            onError: config.onError || null,                    // Error callback
            loadingSpinner: config.loadingSpinner || '#loading-spinner',
            deleteConfirmation: config.deleteConfirmation || {
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
            },
            messages: {
                createSuccess: config.messages?.createSuccess || 'Data berhasil ditambahkan!',
                updateSuccess: config.messages?.updateSuccess || 'Data berhasil diupdate!',
                deleteSuccess: config.messages?.deleteSuccess || 'Data berhasil dihapus!',
                loadError: config.messages?.loadError || 'Gagal memuat data',
                saveError: config.messages?.saveError || 'Gagal menyimpan data',
                deleteError: config.messages?.deleteError || 'Gagal menghapus data',
            }
        };

        this.table = null;
        this.isEditMode = false;
        this.currentId = null;

        this.init();
    }

    init() {
        this.initDataTable();
        this.initFormSubmit();
    }

    initDataTable() {
        this.table = $(this.config.tableId).DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            autowidth: false,
            ajax: {
                url: this.config.dataTableUrl,
                type: 'GET',
                error: (xhr, error, code) => {
                    console.error('DataTables Ajax Error:', error);
                    toastr.error('Gagal memuat data. Silakan refresh halaman.', 'Error');
                }
            },
            columns: this.config.columns,
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            language: {
                processing: '<div class="flex items-center justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div></div>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                emptyTable: "Tidak ada data tersedia",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            drawCallback: (settings) => {
                // Add custom styling to action buttons after table draw
                $(this.config.tableId + ' .btn-edit').addClass('text-blue-600 hover:text-blue-800');
                $(this.config.tableId + ' .btn-delete').addClass('text-red-600 hover:text-red-800');
            }
        });

        // Show/Hide loading spinner on processing
        $(this.config.tableId).on('processing.dt', (e, settings, processing) => {
            if (processing) {
                this.showLoading();
            } else {
                this.hideLoading();
            }
        });
    }
    initFormSubmit() {
        $(`#${this.config.formId}`).on('submit', (e) => {
            e.preventDefault();
            this.submitForm();
        });
    }


    // /**
    //  * Submit form (CREATE or UPDATE)
    //  */
    submitForm() {
        this.clearValidationErrors();
        const btnId = 'btn-save';
        this.setButtonLoading(btnId, true, 'Menyimpan...');
        const formData = new FormData($(`#${this.config.formId}`)[0]);
        const method = this.isEditMode ? 'PUT' : 'POST';
        const url = this.isEditMode 
        ? `${this.config.apiUrl}/${this.currentId}` 
        : this.config.apiUrl;

        if (this.isEditMode) {
            formData.append('_method', 'PUT');
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                this.setButtonLoading(btnId, false);

                const message = this.isEditMode 
                ? this.config.messages.updateSuccess 
                : this.config.messages.createSuccess;

                toastr.success(response.message || message, 'Sukses');

                if (this.config.onSuccess) {
                    this.config.onSuccess(response, this.isEditMode);
                }
                if (this.config.apiUrl) {
                    setTimeout(() => {
                        window.location.href = this.config.apiUrl;
                    }, 1000);
                }

            },
            error: (xhr) => {
                this.setButtonLoading(btnId, false);
                this.handleError(xhr, this.config.messages.saveError);
            }
        });
    }

    // /**
    //  * Delete item with confirmation
    //  */
    delete(id, button = null) {
        Swal.fire({
            title: this.config.deleteConfirmation.title,
            text: this.config.deleteConfirmation.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            if (button) this.toggleButtonSpinner(button, true);

            $.ajax({
                url: `${this.config.apiUrl}/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    this.table.ajax.reload(null, false);

                    toastr.success(
                        response.message || this.config.messages.deleteSuccess,
                        'Sukses'
                        );

                    if (this.config.onSuccess) {
                        this.config.onSuccess(response, false, true);
                    }
                },
                error: (xhr) => {
                    toastr.error(this.config.messages.deleteError, 'Error');

                    if (this.config.onError) {
                        this.config.onError(xhr);
                    }
                },
                complete: () => {
                    if (button) this.toggleButtonSpinner(button, false);
                }
            });
        });
    }


    // /**
    //  * Handle AJAX errors
    //  */
    clearValidationErrors() {
        $('.error-text').addClass('hidden').html('');
        $('[name]').removeClass('border-red-500 focus:border-red-500');
    }

    handleError(xhr, defaultMessage) {
        this.clearValidationErrors();

        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;

            Object.keys(errors).forEach((field) => {
                const messages = errors[field];

                const input = $(`[name="${field}"]`);
                const errorEl = $(`[data-error-for="${field}"]`);

                if (input.length) {
                    input.addClass('border-red-500 focus:border-red-500');
                }

                if (errorEl.length) {
                    errorEl
                    .removeClass('hidden')
                    .html(messages[0]);
                }
            });

            return;
        }

        toastr.error(xhr.responseJSON?.message || defaultMessage, 'Error');

        if (this.config.onError) {
            this.config.onError(xhr);
        }
    }


    // /**
    //  * Show loading spinner
    //  */
    showLoading() {
        $(this.config.loadingSpinner).removeClass('hidden');
    }

    // /**
    //  * Hide loading spinner
    //  */
    hideLoading() {
        $(this.config.loadingSpinner).addClass('hidden');
    }

    /**
     * Reload DataTable
     */
    reload() {
        if (this.table) {
            this.table.ajax.reload();
        }
    }

    /**
     * Capitalize first letter
     */
    capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    setButtonLoading(buttonId, isLoading, loadingText = 'Loading...') {
        const btn = document.getElementById(buttonId);
        if (!btn) return;

        const spinner = btn.querySelector('.btn-spinner');
        const text = btn.querySelector('.btn-text');

        if (isLoading) {
            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.dataset.originalText = text.innerText;
            text.innerText = loadingText;
        } else {
            btn.disabled = false;
            spinner.classList.add('hidden');
            text.innerText = text.dataset.originalText || text.innerText;
        }
    }

    toggleButtonSpinner(button, loading = true) {
        const $btn = $(button);

        if (loading) {
            $btn.prop('disabled', true);
            $btn.find('.btn-text').addClass('hidden');
            $btn.find('.btn-spinner').removeClass('hidden');
        } else {
            $btn.prop('disabled', false);
            $btn.find('.btn-text').removeClass('hidden');
            $btn.find('.btn-spinner').addClass('hidden');
        }
    }


}