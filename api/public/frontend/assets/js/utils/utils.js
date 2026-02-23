const API_URL = '/';

/**
 * Utilidad para realizar una solicitud fetch a la API de productos
 * @param {string} endpoint 
 * @param {string} method 
 * @param {object} datos 
 * @returns 
 */
const customFetch = async (endpoint, method = 'GET', datos = null) => {

    const options = {
        method
    };

    // Incluyo cuerpo y encabezados cuando se quieran mandar datos por body
    if (datos) {
        options.body = JSON.stringify(datos);
        options.headers = { 'Content-Type': 'application/json' }
    }

    // Peticiones
    const response = await fetch(API_URL + endpoint, options);
    const data = await response.json();

    // Manejo de errores
    if (!data.resultado) {
        throw new Error(data.errores || 'Error desconocido');
    }

    // Retorno la respuesta
    return data;
};

/**
 * Mostrar / ocultar spinner
 * @param {boolean} mostrar 
 */
const toggleSpinner = (mostrar = true) => {
    const spinner = document.getElementById('spinner');
    spinner.classList.toggle('d-none', !mostrar);
};

/**
 * Confirmar accion (uso de sweetAlert)
 */
const confirmarAccion = async (titulo, detalle = 'Esta acción no se puede deshacer') => {

    const result = await Swal.fire({
        title: titulo,
        text: detalle,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    return result.isConfirmed;
}
/**
 * ALerta tipo toast (uso de sweetAlert)
 */
const toastAlert = (titulo = 'Proceso exitoso', icono = 'success') => {

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icono,
        title: titulo,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true
    });
}