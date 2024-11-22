function agregarUsuario(){
    modalUsuarioForm();
    $('#usuarioId').val(0);
    $('#usuario').val('');       // Limpiar campo usuario
    $('#nombre').val('');        // Limpiar campo nombre
    $('#apellido').val('');      // Limpiar campo apellido
    $('#rol').val(0);            // Seleccionar el rol por defecto (Usuario)
    $('#contraseña').val('');    // Limpiar campo contraseña
    $('#buttonSubmit').val('Enviar');
}

function modalUsuarioForm(){
    $('#modalUsuario').addClass('modal--show');
}

function editarUsuario(input){
    modalUsuarioForm();
    $('#usuarioId').val(input.getAttribute('data-id'));
    $('#usuario').val(input.getAttribute('data-usuario'));
    $('#nombre').val(input.getAttribute('data-nombre'));
    $('#apellido').val(input.getAttribute('data-apellido'));
    $('#rol').val(input.getAttribute('data-rol'));
    $('#contraseña').val(''); // Dejar la contraseña en blanco, para que el usuario pueda decidir si quiere cambiarla
    $('#buttonSubmit').val('Guardar Cambios');
}

function cerrarModal(){
    $('#modalUsuario').removeClass('modal--show');
}
