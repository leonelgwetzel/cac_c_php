// Main.js - Orquesta las funcionalidades de los demas archivos

const state = {
    productos : []
}


// Verificar si existen los productos, de no existir dejar visible la alerta
async function verificarExistenciaProductos(){

    const alerta = document.getElementById('alertaProductos');
    const listado = document.getElementById('listadoProductos');

    if(state.productos.length === 0){
        alerta.classList.remove('d-none');
        listado.classList.add('d-none');
        return false;
    }else{
        alerta.classList.add('d-none');
        listado.classList.remove('d-none');  
        await listarProductos();

        return true; 

    }

}

// Asignar eventos a botones, formularios y elementos html
const asignarEventos = () =>{

    // Botones de editar y eliminar
    document.getElementById('listadoProductos').addEventListener('click', (e) => {

        const btnVer = e.target.closest('.btnVer');
        if (btnVer) {
            const id = btnVer.dataset.id;
            detalleProducto(id);
            return;
        }

        const btnEliminar = e.target.closest('.btnEliminar');
        if (btnEliminar) {
            const id = btnEliminar.dataset.id;
            eliminarProducto(id);
            return;
        }
    });

    // Modal para cargar producto
    document.getElementById('nuevoProducto').addEventListener('click', ()=>{
        abrirModalProducto(true)
    })

    // Formularios
    document.getElementById('btnGuardar').addEventListener('click', async () =>{
        await cargarProducto();
    })

}

/**
 * Mostrar el modal con el formulario del producto
 * @param {bool} cargar // true : carga, false : edición
 * @param {object/null} producto 
 */
const abrirModalProducto = (cargar = true, producto = null)=>{

    // Creo o recupero la instancia del modal
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProducto'));

    // Recupero el formulario y actualizo los datos según los parametros recibidos
    const form = document.getElementById('formProducto');
    const titulo = document.getElementById('modalTitulo');

    form.reset();
    form.dataset.accion = cargar ? 'cargar' : 'editar';

    if (!cargar && producto) {
        titulo.textContent = 'Editar producto';
        document.querySelector('#formProducto #idProducto').value = producto.id;
        document.querySelector('#formProducto #nombre').value = producto.nombre;
        document.querySelector('#formProducto #descripcion').value = producto.descripcion;
        document.querySelector('#formProducto #precio').value = producto.precio;
    } else {
        titulo.textContent = 'Nuevo producto';
        form.reset();
        document.querySelector('#formProducto #idProducto').value = '';
    }

    modal.show();

    return;
}

// Recuperar cotizacion del dolar usada para calcular los valores y mostarla
const recuperarValorDolar = async() =>{

    try {
        const response = await customFetch('dolar');

        if(response.resultado){
            document.getElementById('cotizacion').innerHTML = (response.datos.valor_usd).toLocaleString('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }


    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }

}


// Ejecutar al cargar el documento
document.addEventListener('DOMContentLoaded', async () => {

    toggleSpinner(true);


    // Obtengo los productos y verifico si hay existencia - listado
    state.productos = await obtenerProductos();
    await verificarExistenciaProductos();
    await recuperarValorDolar();

    asignarEventos();
    toggleSpinner(false);
});
