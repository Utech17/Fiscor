<?php
// Inicia la sesión si no está iniciada aún
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario'];

if (isset($_GET['Volver'])) {
    session_destroy();
    echo "<script>
        location.href='../index.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vista/css/dataTables.bootstrap5.css">
    <script src="https://kit.fontawesome.com/68b92f41c0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <link rel="stylesheet" type="text/css" href="../vista/css/estiloreporte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    
    <title>Gastos</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor" style="margin-left: 5px; margin-left: 60px;">
        <div class="barra-lateral">
            <?php imprimirBarraLateral(); ?>
        </div>
    
        <div class="contenido">
            <h1>Reportes</h1>
        </div>
    </div>

    <div class="container" style="max-width: 1200px; margin-left: 40px; margin-right: 5px;">
        <div class="contenedor-categoria px-6 pt-5">
        <h1 class="titulo"><b>Selecciona el reporte que deseas consultar</b></h1>

<form method="post" target="_blank">
    <select id="imprimirReporte" name="imprimirReporte" class="select-menu" >
        <option value="proyectos">Proyectos</option>
        <option value="categorias">Categorías</option>
        <option value="elementos">Elementos</option>
        <option value="gsatos">Gastos</option>
        <option value="usuarios">Usuarios</option>
    </select>

    <button type="submit" class="boton-impresion">
        <i class="fas fa-print print-icon"></i> <b>Imprimir</b>
    </button>
</form>

<?php
if(isset($_POST['imprimirReporte'])) {
$seleccion = $_POST['imprimirReporte'];
switch($seleccion) {
case "productos":
    header("Location: ../fpdf/productos.php");
    exit();
case "entradas":
    header("Location: ../fpdf/entradas.php");
    exit();
case "salidas":
    header("Location: ../fpdf/salidas.php");
    exit();
case "proveedores":
    header("Location: ../fpdf/proveedores.php");
    exit();
case "clientes":
    header("Location: ../fpdf/clientes.php");
    exit();
case "entrada-salidas":
    header("Location: ../fpdf/reporte_alumno2.php");
    exit();
default:
    // Manejo del caso por defecto
    break;
}
}
?>

<div class="contenedor-imagen">
    <img src="../vista/img/chicaimpresora.png" class="imagen1" alt="chica en una impresora">
</div>
</div>
</div>
</div>

</body>
</html>