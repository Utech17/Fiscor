<?php
require_once("../modelo/presupuesto_modelo.php");

$objPresupuesto = new PresupuestoModelo();
$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;
$message = null;

if (isset($_POST['Enviar'])) {
    echo "<script>console.log('Conectado')</script>";

    $objPresupuesto->set_iditem($_POST['item_seleccionado']);
    $objPresupuesto->set_idproyecto($_POST['proyecto_seleccionado']);
    $objPresupuesto->set_cantidad($_POST['cantidad']);
    $objPresupuesto->set_montopresupuesto($_POST['presupuesto']);
    echo "<script>console.log('Conectado2')</script>";

    $result = $objPresupuesto->incluir();

    if ($result == 1) {
        $message = "Presupuesto agregado con éxito";
    } else {
        $message = "Error al agregar presupuesto";
    }
}

if (isset($_GET['eliminarId'])) {

    $objPresupuesto->set_iditem($_GET['eliminarId']);

    if ($objPresupuesto->eliminar()) {
        $message = "Registro Eliminado con éxito";
    } else {

        $message = "No se pudo Eliminar";
    }
}

if (isset($_POST['editarId'])) {
    if (isset($_POST['editarNombre']) && isset($_POST['editarEstado'])) {
        $objItem->set_iditem($_POST['editarId']); // Asegúrate de utilizar editarId aquí
        $objItem->set_nombre($_POST['editarNombre']);
        $objItem->set_estado($_POST['editarEstado']);

        $resultado = $objItem->modificar();

        if ($resultado) {
            $message = "item actualizado con éxito";
        } else {
            $message = "Error al actualizar item";
        }
    } else {
        $message = "Faltan datos para actualizar el item";
    }
}
