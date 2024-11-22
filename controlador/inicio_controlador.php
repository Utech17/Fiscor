<?php
    require_once("../modelo/proyecto_modelo.php");
    require_once("../modelo/presupuesto_modelo.php");
    require_once("../modelo/gastos_modelo.php");
    require_once("vista_controlador.php");

    $presupuestoModelo = new PresupuestoModelo();
    $proyectoModelo = new Proyecto();
    $Gastos = new Gastos();

    $proyectos = $proyectoModelo->buscarTodos();
    // Verificar si la solicitud se realiza vía AJAX
    if (isset($_GET['id_proyecto'])) {
        $id_proyecto = $_GET['id_proyecto'];

        // Obtener los datos de las categorías del proyecto seleccionado
        $datosCategorias = $presupuestoModelo->obtenerCategoriasPorProyecto($id_proyecto);

        // Enviar los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($datosCategorias, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        exit;
    }

    //total de proyectos
    $totalpro = $proyectoModelo->contarProyectos();

    // Total de presupuesto
    $totalpre = $presupuestoModelo->sumarPresupuestos();
    if ($totalpre === null || $totalpre == 0) {
        $totalpre = "0.00";
    } else {
        // Formatear el número para mostrar separadores de miles y dos decimales
        $totalpre = number_format($totalpre, 2, '.', ',');
    }

    // Total de gastos
    $totalg = $Gastos->sumarTodosLosGastos();
    if ($totalg === null || $totalg == 0) {
        $totalg = "0.00";
    } else {
        // Formatear el número para mostrar separadores de miles y dos decimales
        $totalg = number_format($totalg, 2, '.', ',');
    }

    // Cargar el proyecto por defecto
    $id_proyecto = 1;
    $datosCategorias = $presupuestoModelo->obtenerCategoriasPorProyecto($id_proyecto);
    $datosJSON2 = json_encode($datosCategorias);

    require_once("../vista/inicio.php");
?>
