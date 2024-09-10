<?php
require('fpdf.php');
require('../modelo/reporte_modelo.php');

class PDF extends FPDF
{
    var $fill = false;

    function Header()
    {
        $this->Image('../vista/img/fondoreporte.png', 0, 0, 215);
        $this->SetFont('Helvetica', 'B', 25);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(160, 30);
        $this->SetTextColor(0, 89, 171);
        $this->Cell(1, 28, utf8_decode('Lista de proyectos'), 0, 1, 'C', 0);
        $this->Ln(20);
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5, true);
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

$reporte = new Reportes();
$proyectos = $reporte->obtenerDatosProyectos();
$totalPresupuesto = $reporte->obtenerTotalPresupuesto();
$totalGastado = $reporte->obtenerTotalGastado();

$pdf->SetFillColor(0, 89, 171);
$pdf->SetDrawColor(0, 89, 171);

$pdf->SetFont('Helvetica', 'B', 14);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(10, 50);

$pdf->Cell(10, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(30, 10, utf8_decode('Estatus'), 1, 0, 'C', 1);
$pdf->Cell(60, 10, utf8_decode('Proyecto'), 1, 0, 'C', 1);
$pdf->Cell(45, 10, utf8_decode('Presupuesto'), 1, 0, 'C', 1);
$pdf->Cell(45, 10, utf8_decode('Gastado'), 1, 1, 'C', 1);

$pdf->SetFillColor(166, 212, 255);
$pdf->SetDrawColor(0, 89, 171);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

for ($i = 0; $i < count($proyectos); $i++) {
    if ($pdf->fill) {
        $pdf->SetFillColor(166, 212, 255);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->fill = !$pdf->fill;

    $pdf->setX(10);
    $pdf->Cell(10, 10, utf8_decode($i + 1), 1, 0, 'C', 1);

    $estado = '';
    $estadoColor = [0, 0, 0]; // Default color (black)

    if ($proyectos[$i]['Estado'] == 1) {
        $estado = 'En Proceso';
        $estadoColor = [0, 89, 171]; // Blue
        $pdf->SetFont('Arial', 'B', 10); // Set bold font
    } elseif ($proyectos[$i]['Estado'] == 2) {
        $estado = 'Finalizado';
        $estadoColor = [26, 188, 156]; // Green
        $pdf->SetFont('Arial', 'B', 10); // Set bold font
    } else {
        $estado = 'Desconocido';
        $estadoColor = [0, 0, 0]; // Black
        $pdf->SetFont('Arial', 'B', 10); // Set bold font
    }

    $pdf->SetTextColor($estadoColor[0], $estadoColor[1], $estadoColor[2]);
    $pdf->Cell(30, 10, utf8_decode($estado), 1, 0, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // Reset to black
    $pdf->SetFont('Arial', '', 10); // Set normal font
    $pdf->Cell(60, 10, utf8_decode($proyectos[$i]['Nombre']), 1, 0, 'C', 1);
    $pdf->Cell(45, 10, utf8_decode(number_format($proyectos[$i]['Presupuesto'], 2, '.', ',')), 1, 0, 'C', 1);
    $pdf->Cell(45, 10, utf8_decode(number_format($proyectos[$i]['Gastado'], 2, '.', ',')), 1, 0, 'C', 1);

    $pdf->Ln(10);
}

$pdf->Output('I', 'Proyectos.pdf');
?>