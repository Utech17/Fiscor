<?php
// Llamada al modelo
require_once("../modelo/reporte_modelo.php");
require_once("../modelo/proyecto_modelo.php");

$proyectoModelo = new Proyecto();
$reporte_in = new Reportes_i();

$proyectos = $proyectoModelo->buscarTodos();

$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_proyecto'])) {
    $id_proyecto = intval($_POST['id_proyecto']);
    $reporte = new Reportes_i();
    $reporte->generarReporte($id_proyecto); // Asume que generarPDF hace la salida directa del PDF
    exit();
}

// Llamar controlador con funciones de diseño, para no repetir el mismo código
require_once("../controlador/vista_controlador.php");

// Incluir la vista de reportes
require_once("../vista/reporte_vista.php");
?>