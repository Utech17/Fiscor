
function agregarProyecto(){
  modalProyectoForm();
  $('#proyectoId').val(0);
  $('#buttonSubmit').val('Enviar');
}
function modalProyectoForm(){
  $('#modalProyecto').addClass('modal--show');
}
function buscarProyecto( input ){
  modalProyectoForm();
  $('#estado').val( input.getAttribute('data-estado') );
  $('#nombre').val( input.getAttribute('data-nombre') );
  $('#descripcion').val( input.getAttribute('data-descripcion') );
  $('#proyectoId').val( input.getAttribute('data-id') );
  $('#buttonSubmit').val('Guardar Cambios');
}

function cerrarModal(){
  $('#modalProyecto').removeClass('modal--show');
}