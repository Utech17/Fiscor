<?php
require_once("../modelo/gastos_modelo.php");
require_once("../modelo/categoria_modelo.php");

$objGastos = new Gastos();
$objCategoria = new Categoria();

$data = $objGastos->buscarTodos();

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
        $presupuestoSuperado = $objGastos->verificarPresupuestoSuperado( $lista_presupuesto, $data );

        if ($resultado) {
            $script = "<script>
                alert('Gasto agregado con éxito');
                location.href='../controlador/gastos_controlador.php?modalOn";
                if ($presupuestoSuperado) {
                    $script .= "&ps";
                }
            $script .= "';</script>";
            echo $script;
        } else {
            echo "<script>alert('Error al agregar gasto');</script>";
        }
    } else {
        echo "<script>alert('Faltan datos para agregar el gasto');</script>";
    }
}

if (isset($_POST['eliminarId'])) {
    $objGastos->setID_Gasto($_POST['eliminarId']);
    $resultado = $objGastos->eliminarGasto();
    if ($resultado) {
        echo "<script>alert('Gasto eliminado con éxito');location.href='../controlador/gastos_controlador.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar gasto');</script>";
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