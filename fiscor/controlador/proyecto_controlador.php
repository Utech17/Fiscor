<?php
require_once("../modelo/proyecto_modelo.php");
$message = null;
$objProyecto = new Proyecto();
$data = $objProyecto->buscarTodos();
$dataAux = $objProyecto->buscarPresupuesto();
$dataPresupuesto = array();
foreach ($dataAux as $c) {
    $dataPresupuesto[$c['id_proyecto']][] = $c['monto_presupuesto'];
}

require_once("vista_controlador.php");
$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

if (isset($_POST['Enviar'])) {
    if (isset($_POST['nombre']) && isset($_POST['estado']) && isset($_POST['descripcion'])) {
        $idProyecto = $_POST['proyectoId'];
        $objProyecto->setID_Proyecto($idProyecto);
        $objProyecto->set_Estado($_POST['estado']);
        $objProyecto->set_Nombre($_POST['nombre']);
        $objProyecto->set_Descripcion($_POST['descripcion']);
        if ($idProyecto == 0)
            $resultado = $objProyecto->agregarProyecto();
        else if ($idProyecto > 0)
            $resultado = $objProyecto->actualizarProyecto();

        if ($idProyecto == 0) {
            if ($resultado == 1)
                $message = " Proyecto agregada con éxito";

            else
                $message = " Error al agregar proyecto";
        } else if ($idProyecto > 0) {
            if ($resultado == 1)
                $message = " Proyecto modificada con éxito";

            else
                $message = " Error al modificar proyecto";
        }
    } else
        $message = "Faltan datos para agregar la proyecto";
}

if (isset($_GET['eliminarId'])) {
    $idProyecto = $_GET['eliminarId'];

    // Verificar si el proyecto está asociado a algún presupuesto
    $objProyecto->setID_Proyecto($idProyecto);
    $presupuestoAsociado = $objProyecto->tienePresupuestoAsociado();

    if ($presupuestoAsociado) {
        // Si hay presupuesto asociado, mostrar un mensaje y no proceder con la eliminación
        $message = "Por políticas de la empresa, no se puede eliminar un proyecto que tiene un presupuesto ya definido.";
    } else {
        // Si no hay presupuesto asociado, proceder con la eliminación
        $resultado = $objProyecto->eliminarProyecto();

        if ($resultado) {
            $message = "Proyecto eliminado con éxito";
        } else {
            $message = "Error al eliminar proyecto";
            // Manejar el error de forma similar, mostrando un modal de error
        }
    }
}

require_once("../vista/proyecto_vista.php");
