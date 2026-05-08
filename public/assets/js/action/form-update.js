import axios from 'axios'; 

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.classList.contains('form-ajax')) {
            e.preventDefault();
            handleAjaxForm(e.target);
        }
    });
});

/**
* Fungsi Inti untuk menangani Form AJAX
*/
function handleAjaxForm(form) {
    const submitBtn = form.querySelector('.btn-submit');
    const loadingIcon = form.querySelector('.loading-icon');
    const originalBtnText = submitBtn ? submitBtn.innerText : 'Submit';

    if (submitBtn) {
        submitBtn.disabled = true;
        if(loadingIcon) loadingIcon.classList.remove('hidden');
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    }

    const formData = new FormData(form);
    const url = form.action;

    const method = form.method || 'POST'; 

    axios({
        method: method,
        url: url,
        data: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {

        if (showAlert) {
            showAlert(response.data.message, 'success');
        } else {
            alert(response.data.message);
        }

        // if (response.data.status) {
        //     console.log('Status baru:', response.data.status);
        // }
    })
    .catch(error => {

        let errorMessage = 'Terjadi kesalahan sistem.';

        if (error.response && error.response.data.status === 422) {
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors)[0][0];
        } 
        else if (error.response && error.response.data.message) {
            errorMessage = error.response.data.message;
        }

        if (showAlert) {
            showAlert(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.disabled = false;
            if(loadingIcon) loadingIcon.classList.add('hidden');
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        location.reload();
    });
}

/* Fungsi Bantuan untuk menampilkan alert */
/**
     * Show alert/notification to user
     * @param {string} message - Alert message
     * @param {string} type - Alert type (success, error, warning, info)
*/
function showAlert(message, type = 'info') {
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
            title: "Berhasil!",
            text: message
        });
    } else {
        console.log(`[${type}] ${message}`);
        alert(message); 
    }
}