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
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    
    <title>Reportes</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor">
        <div class="barra-lateral" id="barra-lateral">
            <?php imprimirBarraLateral($idRol); ?>
        </div>
        <div class="contenido">
            <h1>Reportes</h1>
        </div>
    </div>

    <div class="container">
        <div class="contenedor-categoria px-6 pt-5">
            <h1 class="titulo"><b>Selecciona el reporte que deseas consultar</b></h1>
            <form id="reportForm" method="post" action="" target="_blank">
                <select name="id_proyecto" id="proyecto" class="form-select w-auto d-inline-block">
                    <option value="todos">-- Todos --</option>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?php echo $proyecto['ID_Proyecto']; ?>">
                            <?php echo $proyecto['Nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="boton-impresion">
                    <i class="fas fa-print print-icon"></i> <b>Imprimir</b>
                </button>
            </form>
            <div class="contenedor-imagen">
                <img src="../vista/img/chicaimpresora.png" class="imagen1" alt="chica en una impresora">
            </div>
        </div>
    </div><br><br>

    <script>
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        var proyectoSelect = document.getElementById('proyecto');
        var selectedValue = proyectoSelect.value;

        if (selectedValue === 'todos') {
            // Evitar el envío del formulario si es "Todos"
            e.preventDefault();
            window.open('../fpdf/proyectogeneral.php', '_blank');
        }
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hamburguesa = document.getElementById('hamburguesa');
            var barraLateral = document.getElementById('barra-lateral');
            
            hamburguesa.addEventListener('click', function() {
                barraLateral.classList.toggle('show'); // Alterna la visibilidad de la barra lateral
                
                // Si la barra lateral se muestra, mueve el ícono de hamburguesa
                if (barraLateral.classList.contains('show')) {
                    hamburguesa.style.transform = 'translateX(150px)'; // Desplaza el ícono 200px a la derecha
                } else {
                    hamburguesa.style.transform = 'translateX(0)'; // Restaura la posición original
                }
            });
        });
    </script>
</body>
</html>