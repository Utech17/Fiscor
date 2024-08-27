<?php
require_once('modelo/usuario_modelo.php');

if (isset($_POST['iniciar_sesion'])) {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    
    $objUsuario = new Usuario();
    if ($objUsuario->autenticarUsuario($usuario, $clave)) {
        session_start();
        $_SESSION['id_usuario'] = $objUsuario->get_ID_Usuario();
        $_SESSION['usuario'] = $objUsuario->get_Usuario();
        $_SESSION['nombre'] = $objUsuario->get_Nombre();
        $_SESSION['apellido'] = $objUsuario->get_Apellido();
        $_SESSION['ID_Rol'] = $objUsuario->get_ID_Rol();

        header('Location: controlador/inicio_controlador.php');
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
    }
}
?>