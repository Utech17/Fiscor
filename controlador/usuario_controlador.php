<?php
    require_once('../modelo/usuario_modelo.php');
    require_once("vista_controlador.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit();
    }

    $message = null;
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
    $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
    $apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
    $idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;
    $idUsuario = $_SESSION['id_usuario'];

    $usuarioObj = new Usuario();

    // Obtener datos de usuario dependiendo del rol
    try {
        if ($idRol == 1) {
            $usuarios = $usuarioObj->obtenerUsuarios();
        } else {
            $usuarioObj->obtenerUsuarioPorId($idUsuario);
            $usuario = $usuarioObj->get_Usuario();
            $nombre = $usuarioObj->get_Nombre();
            $apellido = $usuarioObj->get_Apellido();
        }
    } catch (Exception $e) {
        $message = "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Enviar'])) {
        if (
            isset($_POST['usuarioId']) && isset($_POST['usuario']) && isset($_POST['nombre']) &&
            isset($_POST['apellido']) && isset($_POST['contrasena'])
        ) {

            $usuarioId = filter_input(INPUT_POST, 'usuarioId', FILTER_SANITIZE_NUMBER_INT);
            $nuevoUsuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
            $nuevoNombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $nuevoApellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $nuevaContrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_STRING);

            $usuarioObj->set_Usuario($nuevoUsuario);
            $usuarioObj->set_Nombre($nuevoNombre);
            $usuarioObj->set_Apellido($nuevoApellido);

            $accion = '';  // Variable para guardar la acción realizada

            if ($usuarioId == 0) {
                $resultado = $usuarioObj->agregarUsuario($nuevoUsuario, $nuevoNombre, $nuevoApellido, $nuevaContrasena);
                $usuarioId = $usuarioObj->getLastInsertedID();
                $accion = 'agregado';
            } else if ($usuarioId > 0) {
                $usuarioObj->set_ID_Usuario($usuarioId);
                if (!empty($nuevaContrasena)) {
                    $usuarioObj->set_Contrasena($nuevaContrasena);
                }
                $resultado = $usuarioObj->actualizarUsuario();
                $accion = 'modificado';
            }

            if ($resultado) {
                $_SESSION['message'] = 'Usuario ' . htmlspecialchars($accion) . ' con éxito';
            } else {
                $_SESSION['message'] = 'Error al ' . htmlspecialchars($accion) . ' el usuario';
            }
            header("Location: ../controlador/usuario_controlador.php");
            exit();
        } else {
            $message = "Todos los campos son obligatorios.";
        }
    }

    if (isset($_GET['eliminarId'])) {
        $idUsuario = filter_input(INPUT_GET, 'eliminarId', FILTER_SANITIZE_NUMBER_INT);
        if ($idUsuario) {
            $resultado = $usuarioObj->eliminarUsuario($idUsuario);
            if ($resultado) {
                $message = "Usuario eliminado con éxito.";
            } else {
                $message = "Error al eliminar el usuario.";
            }
        }
    }

    require_once("../vista/usuario_vista.php");
