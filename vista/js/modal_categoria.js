function mostrarContenedor1() {
    $('#contenedor1').show();
    $('#contenedor2').hide();
    $('#button').show();
}

function mostrarContenedor2() {
    $('#contenedor1').hide();
    $('#contenedor2').show();
}

function cerrarModal() {
    $('#modalCategoria').removeClass('modal--show');
}

function agregarCategoria() {
    $('#modalCategoria').addClass('modal--show');
    mostrarContenedor2();
    $('#categoriaId').val(0);
    $('#buttonSubmit').val('Enviar');
}

function buscarCategoria(input) {
    $('#modalCategoria').addClass('modal--show');
    mostrarContenedor1();
    $('#estado').val(input.getAttribute('data-estado'));
    $('#nombre').val(input.getAttribute('data-nombre'));
    $('#categoriaId').val(input.getAttribute('data-id'));
    $('#buttonSubmit').val('Guardar Cambios');
    $('#button').hide();
}