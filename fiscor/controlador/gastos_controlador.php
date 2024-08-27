<?php
require_once("../modelo/gastos_modelo.php");

$objGastos = new Gastos();

$data = $objGastos->buscarTodos();
$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;
$lista_proyectos = $objGastos->obtenerListaProyectos();
$lista_categorias = $objGastos->obtenerListaCategorias();
$lista_items = $objGastos->obtenerListaItems();
$lista_presupuesto = $objGastos->obtenerListaPresupuesto();

if (isset($_POST['Enviar'])) {
    if (isset($_POST['idproyecto'], $_POST['iditem'], $_POST['fecha'], $_POST['montogasto'], $_POST['comprobante'], $_POST['observacion'])) {
        $objGastos->setID_Proyecto($_POST['idproyecto']);
        $objGastos->setID_Item($_POST['iditem']);
        $objGastos->setFecha($_POST['fecha']);
        $objGastos->setMonto_Gasto($_POST['montogasto']);
        $objGastos->setComprobante($_POST['comprobante']);
        $objGastos->setObservacion($_POST['observacion']);
        $resultado = $objGastos->agregarGasto();
        // Verificar si supera presupuesto para item
        $presupuestoSuperado = $objGastos->verificarPresupuestoSuperado($lista_presupuesto, $data);

        if ($resultado) {
            $_SESSION['message'] = 'Gasto agregado con éxito';
            if ($presupuestoSuperado) {
                $_SESSION['message'] .= '. Advertencia: Se ha superado el presupuesto para este ítem.';
            }
            header("Location: ../controlador/gastos_controlador.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error al agregar gasto';
            header("Location: ../controlador/gastos_controlador.php");
            exit();
        }
    } else {
        $_SESSION['message'] = 'Faltan datos para agregar el gasto';
        header("Location: ../controlador/gastos_controlador.php");
        exit();
    }
}

if (isset($_POST['eliminarId'])) {
    $objGastos->setID_Gasto($_POST['eliminarId']);
    $resultado = $objGastos->eliminarGasto();
    if ($resultado) {
        $_SESSION['message'] = 'Gasto eliminado con éxito';
        header("Location: ../controlador/gastos_controlador.php");
        exit();
    } else {
        $_SESSION['message'] = 'Error al eliminar gasto';
        header("Location: ../controlador/gastos_controlador.php");
        exit();
    }
}

if (isset($_GET['start_date'], $_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    $data = $objGastos->buscarGastosPorFecha($startDate, $endDate);

    echo json_encode($data);
    exit();
}

require_once("vista_controlador.php");
require_once("../vista/gastos_vista.php");
?>