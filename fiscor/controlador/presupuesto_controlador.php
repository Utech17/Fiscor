<?php
require_once("../modelo/presupuesto_modelo.php");

$objPresupuesto = new PresupuestoModelo();

if(isset($_POST['Enviar'])){
    echo "<script>console.log('Conectado')</script>";

    $objPresupuesto->set_iditem($_POST['item_seleccionado']);
    $objPresupuesto->set_idproyecto($_POST['proyecto_seleccionado']);
    $objPresupuesto->set_cantidad($_POST['cantidad']);
    $objPresupuesto->set_montopresupuesto($_POST['presupuesto']);
    echo "<script>console.log('Conectado2')</script>";
    
    $result=$objPresupuesto->incluir();

    if ($result == 1) {
        echo "<script>alert('Presupuesto agregado con éxito');location.href='../controlador/item_controlador.php';</script>";
    } else {
        echo "<script>alert('Error al agregar presupuesto');</script>";
    }
}

if (isset($_GET['eliminarId'])){
			
    $objPresupuesto->set_iditem($_GET['eliminarId']);

    if($objPresupuesto->eliminar()){
        echo "<script>alert('Registro Eliminado con éxito');location.href='../controlador/item_controlador.php'; </script>";
        
    } else {
        echo "<script>alert('No se pudo Eliminar')</script>";
    }
}

if (isset($_POST['editarId'])) {
    if (isset($_POST['editarNombre']) && isset($_POST['editarEstado'])) {
        $objItem->set_iditem($_POST['editarId']); // Asegúrate de utilizar editarId aquí
        $objItem->set_nombre($_POST['editarNombre']);
        $objItem->set_estado($_POST['editarEstado']);
        
        $resultado = $objItem->modificar();
        
        if ($resultado) {
            echo "<script>alert('item actualizado con éxito');location.href='../controlador/item_controlador.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar item');</script>";
        }
    } else {
        echo "<script>alert('Faltan datos para actualizar el item');</script>";
    }
}

?>