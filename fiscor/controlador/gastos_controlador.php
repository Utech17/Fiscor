<?php
    require_once("../modelo/gastos_modelo.php");
    require_once("../modelo/categoria_modelo.php");
$message = null;
    $objGastos = new Gastos();
    $objCategoria = new Categoria();
    $idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

    $data = $objGastos->buscarTodos();

    $lista_categorias = $objGastos->obtenerListaCategorias();
    $lista_proyectos = $objGastos->obtenerListaProyectos();
    $lista_items = $objGastos->obtenerListaItems();

if (isset($_POST['Guardar'])) {
    if (isset($_POST['ID_Proyecto'], $_POST['ID_Item'], $_POST['Fecha'], $_POST['Monto_Gasto'], $_POST['Comprobante'], $_POST['Observacion'])) {
        $objGastos->setID_Proyecto($_POST['ID_Proyecto']);
        $objGastos->setID_Item($_POST['ID_Item']);
        $objGastos->setFecha($_POST['Fecha']);
        $objGastos->setMonto_Gasto($_POST['Monto_Gasto']);
        $objGastos->setComprobante($_POST['Comprobante']);
        $objGastos->setObservacion($_POST['Observacion']);
        $resultado = $objGastos->agregarGasto();

        if ($resultado) {
            $message = "Gasto agregado con éxito";
        } else {
            $message = "Error al agregar gasto";
        }
    } else {
        $message = "Faltan datos para agregar el gasto";
    }
}

if (isset($_POST['editarId'])) {
    if (isset($_POST['editarID_Proyecto'], $_POST['editarID_Item'], $_POST['editarID_Usuario'], $_POST['editarFecha'], $_POST['editarMonto_Gasto'], $_POST['editarComprobante'], $_POST['editarObservacion'])) {
        $objGastos->setID_Gasto($_POST['editarId']);
        $objGastos->setID_Proyecto($_POST['editarID_Proyecto']);
        $objGastos->setID_Item($_POST['editarID_Item']);
        $objGastos->setID_Usuario($_POST['editarID_Usuario']);
        $objGastos->setFecha($_POST['editarFecha']);
        $objGastos->setMonto_Gasto($_POST['editarMonto_Gasto']);
        $objGastos->setComprobante($_POST['editarComprobante']);
        $objGastos->setObservacion($_POST['editarObservacion']);

        $resultado = $objGastos->actualizarGasto();

        if ($resultado) {
            $message = "Gasto actualizado con éxito";
        } else {
            $message = " Error al actualizar gasto";
        }
    } else {
        $message = " Faltan datos para actualizar el gasto";
    }
}

if (isset($_GET['eliminarId'])) {
    $objGastos->setID_Gasto($_GET['eliminarId']);
    $resultado = $objGastos->eliminarGasto();

    if ($resultado) {
        $message = "Gasto eliminado con éxito";
    } else {
        $message = " Error al eliminar gasto";
    }
}

    if (isset($_GET['start_date'], $_GET['end_date'])) {
        $startDate = $_GET['start_date'];
        $endDate = $_GET['end_date'];

        $data = $objGastos->buscarGastosPorFecha($startDate, $endDate);

        echo json_encode($data);
        exit;
    }

    require_once("vista_controlador.php");
    require_once("../vista/gastos_vista.php");
?>