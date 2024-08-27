<?php
    require_once("../modelo/proyecto_modelo.php");
    require_once("../modelo/presupuesto_modelo.php");
    require_once("vista_controlador.php");

    // Crear instancia del modelo de presupuesto y proyecto
    $presupuestoModelo = new PresupuestoModelo();
    $proyectoModelo = new Proyecto();

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

    // Si no es una solicitud AJAX, cargar los datos iniciales
    $datosPresupuesto = $presupuestoModelo->consultar(); 

    // Formatear los datos para Chart.js
    $datosFormateados = array();
    foreach ($datosPresupuesto as $dato) {
        $datosFormateados[] = array(
            'proyecto' => $dato['Nombre'], 
            'presupuesto' => $dato['total_presupuesto']
        );
    }

    // Enviar los datos en formato JSON para uso en frontend
    $datosJSON = json_encode($datosFormateados);

    $proyectos = $proyectoModelo->buscarTodos();

    $totalpro = $proyectoModelo->contarProyectos();
    $totalpre = $presupuestoModelo->sumarPresupuestos(); 

    // Cargar el proyecto por defecto
    $id_proyecto = 1;
    $datosCategorias = $presupuestoModelo->obtenerCategoriasPorProyecto($id_proyecto);
    $datosJSON2 = json_encode($datosCategorias);

    require_once("../vista/inicio.php");
?>
