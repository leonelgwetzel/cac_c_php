// producto.js -- Encargado de las operaciones contra un producto

/**
 * Obtener todos los productos o un producto especifico
 * @param {int/null} id
 */
async function obtenerProductos(id = null){

    try {
        const endpoint = id ? `productos/${id}` : 'productos';
        const data = await customFetch(endpoint);
        return data.datos;
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
}

/**
 * Mostrar detalle de producto
 * @param {int} id 
 */
async function detalleProducto(id) {

    // Recupero el producto haciendo uso del metodo para recuperar productos por id (podria recuperarlo del estado también)
    const producto = await obtenerProductos(id);

    abrirModalProducto(false,producto);

    return;    
}

/**
 * Eliminar producto
 * @param {int} id 
 */
async function eliminarProducto(id) {

    // Le solicito confirmación al usuario
    const eliminar = await confirmarAccion('¿Desea eliminar el producto?');
    if(!eliminar) return;

    try {

        // Peticiono
        toggleSpinner(true);
        const endpoint = `producto/${id}`;
        const resultado = await customFetch(endpoint,'DELETE');

        // Elimino del estado y actualizo la lista
        await actualizarProductoEnEstado(true,id);
        
        toggleSpinner(false);
        return toastAlert(resultado.mensaje);
        
    } catch (error) {
        toggleSpinner(false);

        Swal.fire('Error', error.message, 'error');
    }

    


}

/**
 * Validar datos del formulario
 * @param {object} datos
 */
const validarProducto = (datos) =>{

    let errores = '';

    if (!datos.nombre?.trim()) {
        errores += 'El nombre es obligatorio<br>';
    }

    if (!datos.descripcion?.trim()) {
        errores += 'La descripción es obligatoria<br>';
    }

    const precio = parseFloat(datos.precio);

    if (isNaN(precio) || precio <= 0) {
        errores += 'El precio debe ser un número válido mayor a 0<br>';
    }

    return errores.trim();
}

/**
 * Cargar o editar un producto (submit del formulario)
 */
async function cargarProducto(){

    toggleSpinner(true);

    // Recupero los datos y la acción para definir procedimiento
    const form = document.getElementById('formProducto');
    const accion = form.dataset.accion;

    const producto = {
        nombre : document.querySelector('#formProducto #nombre').value,
        descripcion : document.querySelector('#formProducto #descripcion').value,
        precio : parseFloat(document.querySelector('#formProducto #precio').value).toFixed(2)
    }

    // Valido que el set de datos sea correcto
    const errores = validarProducto(producto);

    if (errores) {
        toggleSpinner(false);

        Swal.fire({
            title: 'Errores',
            html: errores,
            icon: 'error'
        });
        return;
    }

    // Peticiono
    const idProducto = accion == 'cargar' ? null : document.querySelector('#formProducto #idProducto').value;
    const metodo = accion == 'cargar' ? 'POST' : 'PUT';
    endpoint = accion == 'cargar' ? 'producto' : `producto/${idProducto}` 

    try {
        
        const response = await customFetch(endpoint,metodo,producto);

        // Si cargue, añado el objeto al estado y listo
        if(accion == 'cargar' && response.resultado){
            state.productos.push(response.datos);
            await verificarExistenciaProductos();
        }

        // Si edite, modifico el objeto en el estado y vuelvo a listar
        if(accion == 'editar' && response.resultado){
            await actualizarProductoEnEstado(false,idProducto,response.datos)
        }

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProducto'));
        modal.hide();
        toggleSpinner(false);

        return toastAlert(response.mensaje);

    } catch (error) {
        toggleSpinner(false);
        Swal.fire('Error', error.message, 'error');
    }

}

