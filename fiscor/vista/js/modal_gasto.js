function agregarGasto(){
    modalGastoForm();
    $('#gastoId').val(0);
    $('#buttonSubmit').val('Enviar');
}

function modalGastoForm(){
    $('#modalGasto').addClass('modal--show');
}

function cerrarModal(){
    $('#modalGasto').removeClass('modal--show');
    $('#modalEliminar').removeClass('modal--show');
}

function cambiarFiltroProyecto( idproyecto ){
    // Filtrar los gastos que pertenecen al proyecto
    $('#filtroCategoria').html('<option value="0">-- Todos --</option>');
    $('#filtroItem').html('<option value="0">-- Todos --</option>');
    if( idproyecto > 0 ){
        // Items relacionados con proyecto
        const idItems = listaPresupuesto.filter(item => item.id_proyecto == idproyecto).map(item =>item.id_item );
        const idCategorias = listaItem.filter(item => idItems.includes(item.id_item)).map(item => item.id_categoria);
        const categoriasUnicas = [...new Set(idCategorias)];
        const lista = categoriasUnicas.map(id_categoria => {
            const categoria = listaCategoria.find(cat => cat.id_categoria === id_categoria);
            return categoria ? { id_categoria: categoria.id_categoria, nombre: categoria.nombre } : null;
        }).filter(categoria => categoria !== null);

        $.each(lista, function(k, c){
            $('#filtroCategoria').append(`<option value="${ c['id_categoria'] }">${ c['nombre'] }</option>`);
        });
    }
    cambiarFiltro();
}

function cambiarFiltroCategoria( idcategoria ){
    // Filtrar los gastos que pertenecen al proyecto
    let idproyecto = $('#filtroProyecto').val();
    $('#filtroItem').html('<option value="0">-- Todos --</option>');
    if( idcategoria > 0 ){
        // Items relacionados con categoria
        const idItems = listaPresupuesto.filter(item => item.id_proyecto == idproyecto).map(item =>item.id_item );
        const lista = listaItem.filter(item => idItems.includes(item.id_item) && item.id_categoria == idcategoria );
        $.each(lista, function(k, c){
            $('#filtroItem').append(`<option value="${ c['id_item'] }">${ c['nombre'] }</option>`);
        });
    }
    cambiarFiltro();
}

function cambiarFiltro(){
    let idproyecto = $('#filtroProyecto').val();
    let idcategoria = $('#filtroCategoria').val();
    let iditem = $('#filtroItem').val();
    // Filtrar por fecha
    let dataFiltro = filtroFecha();
    if( !dataFiltro ) return;
    // Filtrar proyecto y items
    dataFiltro = dataFiltro.filter(item => ( ( idproyecto == 0 || item.ID_Proyecto == idproyecto ) && ( iditem == 0 || item.ID_Item == iditem ) ) );
    const idItems = dataFiltro.map(item =>item.ID_Item );
    const itemCategorias = listaItem.filter(item => idItems.includes(item.id_item) && (idcategoria == 0 || item.id_categoria == idcategoria)).map(item => item.id_item);
    // Filtrar a items pertenecientes a categorias
    if( idcategoria > 0 ){
        dataFiltro = dataFiltro.filter(item => ( itemCategorias.includes(item.ID_Item) ) );
    }
    // Presentar datos
    let echo = '';
    $.each(dataFiltro, function(k, row){
        row['proyecto'] = listaProyecto[ row['ID_Proyecto'] ] != undefined ? listaProyecto[ row['ID_Proyecto'] ] : '';
        row['item'] = ''; $.each(listaItem, function(k2, c2){
            if(c2['id_item'] == row['ID_Item']) row['item'] = c2['nombre'];
        });
        echo += `<tr>
            <td>${ row['Fecha'] }</td>
            <td>${ row['item'] }</td>
            <td>${ row['Monto_Gasto'] }</td>
            <td>${ row['proyecto'] }</td>
            <td>
                <a onClick='eliminarGasto(this)' class='btn-rojo' data-id='${row['ID_Gasto']}'><img src='../vista/img/eliminar.png' alt='eliminar'></a>
            </td>
        </tr>`;
    })
    $('#tabla').DataTable().destroy();
    $('#tablaDataGasto').html( echo )
    $('#tabla').DataTable({});
}

function filtroFecha(){
    let fechaDesde = $('#filtroFechaD').val();
    let fechaHasta = $('#filtroFechaH').val();

    // Verificar si fechaHasta es mayor que fechaDesde
    if( fechaDesde != '' && fechaHasta != '' && new Date(fechaHasta) < new Date(fechaDesde)) {
        alert('La fecha "Hasta" no puede ser menor que la fecha "Desde".');
        $('#filtroFechaH').val(''); return false;
    } else {
        // Filtrar listaGasto según las fechas proporcionadas
        let listaFiltrada = listaGasto.filter(gasto => {
            let fechaGasto = new Date(gasto.Fecha);
            // Verificar si cumple con la condición de fechaDesde
            let cumpleDesde = fechaDesde ? fechaGasto >= new Date(fechaDesde) : true;
            // Verificar si cumple con la condición de fechaHasta
            let cumpleHasta = fechaHasta ? fechaGasto <= new Date(fechaHasta) : true;
            // Retornar true si cumple con ambas condiciones
            return cumpleDesde && cumpleHasta;
        });
        return listaFiltrada;
    }
}

function eliminarGasto(input) {
    $('#modalEliminar').addClass('modal--show');
    $('#eliminarId').val(input.getAttribute('data-id'));
}

function seleccionarProyecto(idproyecto) {
    if (idproyecto == '' || idproyecto == 0) return;

    const itemsProyecto = listaPresupuesto.filter(proyecto => proyecto.id_proyecto == idproyecto);
    
    // Obtener los id_categorias correspondientes a los id_item del proyecto
    const categoriasProyecto = itemsProyecto.map(proyecto => {
        const item = listaItem.find(i => i.id_item == proyecto.id_item);
        return item ? item.id_categoria : null;
    }).filter(id_categoria => id_categoria !== null);

    // Obtener categorías únicas y sus nombres
    const categoriasUnicas = [...new Set(categoriasProyecto)];
    const listaCategorias = categoriasUnicas.map(id_categoria => {
        const categoria = listaCategoria.find(cat => cat.id_categoria === id_categoria);
        return categoria ? { id_categoria: categoria.id_categoria, nombre: categoria.nombre } : null;
    }).filter(categoria => categoria !== null);

    // Llenar el select de categorías
    $('#idcategoria').empty();
    $('#idcategoria').append('<option value="0">-- Selecciona --</option>');
    $.each(listaCategorias, function(k, c) {
        $('#idcategoria').append(`<option value="${ c.id_categoria }">${ c.nombre }</option>`);
    });

    // Limpiar el select de items
    $('#iditem').empty();
    $('#iditem').append('<option value="0">-- Selecciona --</option>');
}

function seleccionarCategoria(idcategoria) {
    if (idcategoria == '' || idcategoria == 0) return;

    // Filtrar los items por categoría seleccionada
    const itemsCategoria = listaItem.filter(item => item.id_categoria == idcategoria);
    
    // Filtrar los items por el proyecto seleccionado
    const idproyecto = $('#idproyecto').val();
    const itemsProyecto = listaPresupuesto.filter(proyecto => proyecto.id_proyecto == idproyecto)
                                           .map(p => p.id_item);

    const lista = itemsCategoria.filter(item => itemsProyecto.includes(item.id_item));
    
    // Llenar el select de items
    $('#iditem').empty();
    $('#iditem').append('<option value="0">-- Selecciona --</option>');
    $.each(lista, function(k, c) {
        $('#iditem').append(`<option value="${ c.id_item }">${ c.nombre }</option>`);
    });
}

function allowOnlyFloat(evt) {
    // Permitir: Backspace, Delete, Tab, Escape, Enter y .
    if ([46, 8, 9, 27, 13, 110, 190].indexOf(evt.keyCode) !== -1 ||
        // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (evt.keyCode === 65 && (evt.ctrlKey === true || evt.metaKey === true)) ||
        (evt.keyCode === 67 && (evt.ctrlKey === true || evt.metaKey === true)) ||
        (evt.keyCode === 86 && (evt.ctrlKey === true || evt.metaKey === true)) ||
        (evt.keyCode === 88 && (evt.ctrlKey === true || evt.metaKey === true)) ||
        // Permitir: teclas de inicio y fin
        (evt.keyCode >= 35 && evt.keyCode <= 39)) {
        // Dejar funcionar el evento
        return;
    }
    // Asegurarse de que es un número
    if ((evt.shiftKey || (evt.keyCode < 48 || evt.keyCode > 57)) && (evt.keyCode < 96 || evt.keyCode > 105)) {
        evt.preventDefault();
    }
}

function validateFloatInput(input) {
    const value = input.value;
    const regex = /^[+-]?\d+(\.\d+)?$/;
    if (!regex.test(value)) {
        input.setCustomValidity("Por favor, ingrese un número decimal válido.");
    } else {
        input.setCustomValidity("");
    }
}

// Para verificar si existe parametro GET
function existeParametroGet( parametro ) {
    const params = new URLSearchParams(window.location.search);
    return params.has(parametro);
}