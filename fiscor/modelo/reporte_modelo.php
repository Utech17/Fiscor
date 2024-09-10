<?php
require('../fpdf/fpdf.php');
require_once("../modelo/conexionPDO.php");

class Reportes_i extends Conexion {
    private $conexion;

    public function __construct() {
        parent::__construct();
        $this->conexion = parent::conectar();
    }

    // Método para obtener el nombre del proyecto
    public function obtenerNombreProyecto($id_proyecto) {
        $sql_proyecto = "SELECT Nombre FROM proyecto WHERE ID_Proyecto = :id_proyecto";
        $stmt_proyecto = $this->conexion->prepare($sql_proyecto);
        $stmt_proyecto->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
        $stmt_proyecto->execute();
        return $stmt_proyecto->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener las categorías e ítems asociados al proyecto
    public function obtenerCategoriasItems($id_proyecto) {
        $sql_categorias = "
            SELECT c.Nombre AS categoria_nombre, i.nombre AS item_nombre, p.cantidad, p.monto_presupuesto, IFNULL(SUM(g.Monto_Gasto), 0) as gasto_total
            FROM categoria c
            JOIN item i ON c.ID_Categoria = i.id_categoria
            JOIN presupuesto p ON i.id_item = p.id_item
            LEFT JOIN gasto g ON g.ID_Item = i.id_item AND g.ID_Proyecto = :id_proyecto
            WHERE p.id_proyecto = :id_proyecto
            GROUP BY c.Nombre, i.nombre, p.cantidad, p.monto_presupuesto
            ORDER BY c.Nombre, i.nombre";

        $stmt_categorias = $this->conexion->prepare($sql_categorias);
        $stmt_categorias->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
        $stmt_categorias->execute();
        return $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para generar el PDF
    public function generarPDF($id_proyecto) {
        // Crear instancia de FPDF
        $pdf = new FPDF('P', 'mm', array(214, 275));
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetAutoPageBreak(true, 10); // Agregar margen inferior
        
        // Obtener nombre del proyecto
        $proyecto = $this->obtenerNombreProyecto($id_proyecto);
        
        // Título de la tabla con el nombre del proyecto
        if ($proyecto) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, strtoupper(utf8_decode($proyecto['Nombre'])), 0, 1, 'C'); // Cambiar por el nombre del proyecto
        }
        
        // Encabezados de la tabla con color de fondo y texto
        $pdf->SetFillColor(0, 89, 171); // Color de fondo #0059ab
        $pdf->SetTextColor(255, 255, 255); // Texto en blanco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 10, utf8_decode('ESTRUCTURA DE COSTO'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Cantidad'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, utf8_decode('Presupuesto'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, utf8_decode('Gastado'), 1, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0); // Restablecer color de texto a negro
        
        if ($proyecto) {
            // Obtener las categorías e ítems
            $categorias_items = $this->obtenerCategoriasItems($id_proyecto);
        
            $categoria_actual = '';
            $total_proyecto_presupuesto = 0;
            $total_proyecto_gastado = 0;
            $total_categoria_presupuesto = 0;
            $total_categoria_gastado = 0;
            $pdf->SetFont('Arial', '', 10); // Fuente normal
        
            // Recorrer las categorías e ítems
            foreach ($categorias_items as $fila) {
                if ($categoria_actual != $fila['categoria_nombre']) {
                    // Si es una nueva categoría y no es la primera, mostramos el total de la categoría anterior
                    if ($categoria_actual != '') {
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(90, 8, utf8_decode('Total de ' . $categoria_actual), 1);
                        $pdf->Cell(30, 8, '', 1); // Celda vacía para la cantidad
                        $pdf->Cell(35, 8, '$' . number_format($total_categoria_presupuesto, 2), 1, 0, 'C');
                        $pdf->Cell(35, 8, '$' . number_format($total_categoria_gastado, 2), 1, 1, 'C');
                        $total_categoria_presupuesto = 0; // Reiniciamos el total de la nueva categoría
                        $total_categoria_gastado = 0; // Reiniciamos el total de la nueva categoría
                    }
        
                    // Nueva categoría (negrita) con fondo color #a6d4ff
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetFillColor(166, 212, 255); // Color de fondo
                    $pdf->Cell(190, 10, utf8_decode($fila['categoria_nombre']), 1, 1, 'L', true); // Celda con color de fondo
                    $categoria_actual = $fila['categoria_nombre'];
                }
        
                // Verificar si el gasto es mayor al presupuesto y ajustar el color de fondo
                if ($fila['gasto_total'] > $fila['monto_presupuesto']) {
                    $pdf->SetFillColor(255, 0, 0); // Color de fondo rojo
                    $pdf->SetTextColor(255, 255, 255); // Texto en blanco
                } else {
                    $pdf->SetFillColor(255, 255, 255); // Color de fondo blanco (o el color de fondo por defecto)
                    $pdf->SetTextColor(0, 0, 0); // Texto en negro
                }
        
                // Mostrar los ítems, cantidad, presupuesto y gastado
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(90, 8, utf8_decode($fila['item_nombre']), 1, 0, 'L', true);
                $pdf->Cell(30, 8, $fila['cantidad'], 1, 0, 'C', true);
                $pdf->Cell(35, 8, '$' . number_format($fila['monto_presupuesto'], 2), 1, 0, 'C', true);
                $pdf->Cell(35, 8, '$' . number_format($fila['gasto_total'], 2), 1, 1, 'C', true);
        
                // Sumar el presupuesto del ítem al total de la categoría y al total general
                $total_categoria_presupuesto += $fila['monto_presupuesto'];
                $total_categoria_gastado += $fila['gasto_total'];
                $total_proyecto_presupuesto += $fila['monto_presupuesto'];
                $total_proyecto_gastado += $fila['gasto_total'];
            }
        
            // Mostrar el total de la última categoría
            if ($categoria_actual != '') {
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(90, 8, utf8_decode('Total de ' . $categoria_actual), 1);
                $pdf->Cell(30, 8, '', 1); // Celda vacía para la cantidad
                $pdf->Cell(35, 8, '$' . number_format($total_categoria_presupuesto, 2), 1, 0, 'C');
                $pdf->Cell(35, 8, '$' . number_format($total_categoria_gastado, 2), 1, 1, 'C');
            }
        
            // Añadir una fila para el total general con color personalizado
            $pdf->SetFillColor(0, 89, 171); // Color de fondo #0059ab
            $pdf->SetTextColor(255, 255, 255); // Texto en blanco
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(90, 10, utf8_decode('TOTAL GENERAL'), 1, 0, 'C', true);
            $pdf->Cell(30, 10, '', 1, 0, 'C', true); // Celda vacía para la cantidad
            $pdf->Cell(35, 10, '$' . number_format($total_proyecto_presupuesto, 2), 1, 0, 'C', true); // Total presupuesto del proyecto
            $pdf->Cell(35, 10, '$' . number_format($total_proyecto_gastado, 2), 1, 1, 'C', true); // Total gastado
            $pdf->SetTextColor(0, 0, 0); // Restablecer color de texto a negro para el resto del documento
        } else {
            $pdf->Cell(0, 10, utf8_decode('Proyecto no encontrado.'), 0, 1);
        }
        
        // Salida del PDF con nombre del archivo basado en el nombre del proyecto
        $nombre_archivo = 'reporte_' . (isset($proyecto['Nombre']) ? preg_replace('/[^a-zA-Z0-9_]/', '_', $proyecto['Nombre']) : 'desconocido') . '.pdf';
        $pdf->Output('I', $nombre_archivo);
    }    

    // Método principal para generar el reporte
    public function generarReporte($id_proyecto) {
        $this->generarPDF($id_proyecto);
    }
}
?>

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->conectar();
    }

    public function obtenerDatosProyectos() {
        $sql = "SELECT p.ID_Proyecto, p.Nombre, p.Estado, 
                       COALESCE(pb.monto_presupuesto, 0) AS Presupuesto, 
                       COALESCE(g.monto_gasto, 0) AS Gastado
                FROM proyecto p
                LEFT JOIN (SELECT ID_Proyecto, SUM(monto_presupuesto) AS monto_presupuesto 
                           FROM presupuesto 
                           GROUP BY ID_Proyecto) pb ON p.ID_Proyecto = pb.ID_Proyecto
                LEFT JOIN (SELECT ID_Proyecto, SUM(Monto_Gasto) AS monto_gasto 
                           FROM gasto 
                           GROUP BY ID_Proyecto) g ON p.ID_Proyecto = g.ID_Proyecto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalPresupuesto() {
        $sql = "SELECT COALESCE(SUM(monto_presupuesto), 0) AS TotalPresupuesto
                FROM (SELECT ID_Proyecto, SUM(monto_presupuesto) AS monto_presupuesto 
                      FROM presupuesto 
                      GROUP BY ID_Proyecto) pb";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function obtenerTotalGastado() {
        $sql = "SELECT COALESCE(SUM(Monto_Gasto), 0) AS TotalGastado
                FROM (SELECT ID_Proyecto, SUM(Monto_Gasto) AS monto_gasto 
                      FROM gasto 
                      GROUP BY ID_Proyecto) g";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>