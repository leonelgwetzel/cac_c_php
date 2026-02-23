// listado.js - Encargado del listado de productos



/**
 * Listar productos
 */
async function listarProductos() {
    
    const listadoHtml = document.getElementById('listadoProductos');
    listadoHtml.innerHTML = '';

    state.productos.forEach(producto => {
        listadoHtml.innerHTML += `
            <div class="col-sm-12 col-md-4">
                <div class="card mb-3 shadow h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">${producto.nombre}</h5>
                        <em class="card-text text-muted flex-grow-1">${producto.descripcion}</em>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="fw-bold">
                                ARS $${
                                (+producto.precio).toLocaleString('es-AR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                })}
                            </span>
                            <span class="text-success fw-bold">
                                <i class="fa-solid fa-dollar-sign me-1"></i>USD ${
                                (+producto.precio_usd).toLocaleString('es-AR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                })}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 mt-3">
                        <button class="btn btn-dark btn-sm rounded-pill me-3 px-3 btnVer" data-id="${producto.id}">
                            <i class="fa-solid fa-pen me-1"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill me-3 px-3 btnEliminar" data-id="${producto.id}">
                            <i class="fa-solid fa-trash me-1"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;
    });


}

/**
 * Actualizar state: modificar o eliminar un objeto de producto
 * @param {boolean} eliminar // # Si es false, actualiza
 * @param {int} id
 * @param {object/null} datos
 */
async function actualizarProductoEnEstado(eliminar,id,datos = null){

    id = Number(id);

    // Elimino o actualizo datos en el estado
    if(eliminar){
        state.productos = state.productos.filter(p => p.id !== id);
    }else{

        state.productos = state.productos.map(producto => {

            if (producto.id === id) {
                return {
                    ...producto,
                    ...datos
                };
            }
            return producto;
        });
    }

    // Verifico existencia y vuelvo a listar
    await verificarExistenciaProductos();

}