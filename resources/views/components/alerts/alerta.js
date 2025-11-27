// public/js/sweetalert-handler.js

document.addEventListener('DOMContentLoaded', () => {
    const alertType = document.body.getAttribute('data-alert-type');
    const alertMessage = document.body.getAttribute('data-alert-message');

    if (alertType === 'success') {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: alertMessage,
            confirmButtonText: 'Aceptar'
        });
    } else if (alertType === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: alertMessage,
            confirmButtonText: 'Aceptar'
        });
    }
});