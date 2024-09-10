<?php
// Llamada al modelo
require_once("../modelo/reporte_modelo.php");

// Crear instancia del objeto ReportesModelo
$objReporte = new Reportes();
$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;
$lista_proyectos = $objReporte->obtenerListaProyectos();

// Llamar controlador con funciones de diseño, para no repetir el mismo código
require_once("../controlador/vista_controlador.php");

// Incluir la vista de reportes
require_once("../vista/reporte_vista.php");
?>