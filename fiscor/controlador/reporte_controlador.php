<?php
// Llamada al modelo
require_once("../modelo/reporte_modelo.php");

// Crear instancia del objeto ReportesModelo
$objReporte = new Reportes();

// Lógica para obtener los datos necesarios del modelo

// Llamar controlador con funciones de diseño, para no repetir el mismo código
require_once("../controlador/vista_controlador.php");

// Incluir la vista de reportes
require_once("../vista/reporte_vista.php");
?>