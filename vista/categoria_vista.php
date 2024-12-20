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

$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

$m = null;

if (isset($message)) {
    $_SESSION['message'] = $message;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Recuperar el mensaje de la sesión y luego eliminarlo
$m = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
$nombreUsuario = $_SESSION['usuario'];
$idProyecto = isset($_GET['idProyecto']) ? $_GET['idProyecto'] : 0;

if (isset($_GET['Volver'])) {
    session_destroy();
    echo "<script>
        location.href='../index.php';
    </script>";
}

// Asegúrate de que $estadoProyecto está definida correctamente
if (isset($estadoProyecto) && $estadoProyecto == 2) {
    $ocultarBotones = true;
} else {
    $ocultarBotones = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vista/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <title>Categorías</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor" >
        <div class="barra-lateral" id="barra-lateral">
            <?php imprimirBarraLateral($idRol); ?>
        </div>
        <div class="contenido">
            <h1> <a href="proyecto_controlador.php"><?php echo $proyecto['Nombre'] ?></a> > Categorías</h1>
        </div>
    </div>
    <?php if (isset($message)): ?>
        <div id="toast"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="container">
        <div class="contenedor-categoria px-6 pt-5">
            <div id="tabla_div">
                <div class="row">
                    <div class="col-sm-3">
                        <?php if (!$ocultarBotones): ?>
                            <a href="#" class="modal_abrir btn btn-primary" onClick="agregarCategoria();">Agregar Categoría</a>
                        <?php endif; ?>
                    </div>
                    <div class="table-container">
                        <table id="tabla" class="table table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Nombre</th>
                                    <th>Presupuesto</th>
                                    <th>Monto Gastado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if (is_array($categorias) && is_array($categorias)) {
                                    foreach ($categorias as $row) {
                                        if (is_array($row)) {
                                            // Inicializar los montos de presupuesto y gasto
                                            $row['monto_presupuesto'] = 0.00;
                                            if (isset($dataPresupuesto[$row['ID_Categoria']])) {
                                                $row['monto_presupuesto'] = array_sum($dataPresupuesto[$row['ID_Categoria']]);
                                            }
                                            $row['monto_gastado'] = 0.00;
                                            if (isset($dataGasto[$row['ID_Categoria']])) {
                                                $row['monto_gastado'] = array_sum($dataGasto[$row['ID_Categoria']]);
                                            }

                                            // Estilo de borde basado en monto gastado vs presupuesto
                                            $borderStyle = ($row['monto_gastado'] > $row['monto_presupuesto']) 
                                                ? 'border-bottom: 2px solid red; color: red;' 
                                                : 'border-bottom: 2px solid green; color: green;';

                                            $estado = ($row['Estado'] == 1) ? 'Activo' : 'Inactivo';

                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['Nombre'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . number_format($row['monto_presupuesto'], 2) . "</td>";
                                            echo "<td style='$borderStyle'>" . number_format($row['monto_gastado'], 2) . "</td>";
                                            echo "<td class='d-flex justify-content'>
                                                <a href='../controlador/item_controlador.php?idCategoria=" . $row['ID_Categoria'] . "&idProyecto=" . $idProyecto . "' class='btn-azul me-2'><img src='../vista/img/ojo.png' alt='ojo'></a>";
                                                // Verificar si los botones de editar y eliminar deben ocultarse
                                                if (!$ocultarBotones) {
                                                    echo " <a onClick='buscarCategoria(this)' class='btn-azul me-2' data-id='" . $row['ID_Categoria'] . "' data-nombre='" . $row['Nombre'] . "' data-estado='" . $row['Estado'] . "'><img src='../vista/img/editar.png' alt='editar'></a>
                                                        <a href='?eliminarId=" . $row['ID_Categoria'] . "&idProyecto=" . $idProyecto . "' class='btn-rojo'><img src='../vista/img/eliminar.png' alt='eliminar'></a>";
                                                } else {
                                                    echo "<span class='text-muted'> Proyecto finalizado</span>";
                                                }
                                            echo "</td>";
                                            echo "</tr>";
                                        } else {
                                            echo "<tr><td colspan='3'>Dato incorrecto en la fila.</td></tr>";
                                        }
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No hay datos disponibles.</td></tr>";
                                }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- Empty footer -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="modalCategoria" class="modal_section">
        <div class="modal__contenedor">
            <!-- Primer Contenedor (originalmente segundo) -->
            <div id="contenedor2" class="modal__content" style="display: none;">
                <center>
                    <div class="card-header">
                        <h3>Seleccionar Categoría</h3>
                    </div>
                </center>
                <form id="selectCategoriaForm" action="" method="POST">
                    <div class="form-group">
                        <label for="categorias">Categorías</label>
                        <select class="form-control" id="id_categoria" name="id_categoria">
                            <!-- Opciones de categorías -->
                            <?php if (!empty($Categorias)) : ?>
                                <?php foreach ($Categorias as $Categoria): ?>
                                    <option value="<?php echo $Categoria['ID_Categoria']; ?>">
                                        <?php echo htmlspecialchars($Categoria['Nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option value="">No hay categorías disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="modal__botones-contenedor">
                        <input type="button" value="Agregar nueva categoría" class="btn btn-secondary" onClick="mostrarContenedor1()">
                        <input type="submit" value="Enviar" name="Enviar1" class="btn btn-primary">
                        <input type="button" value="Cerrar" class="btn btn-danger" onClick="cerrarModal()">
                    </div>
                </form>
            </div>

            <!-- Segundo Contenedor (originalmente primero) -->
            <div id="contenedor1" class="modal__content">
                <center>
                    <div class="card-header">
                        <h3>Datos de Categoría</h3>
                    </div>
                </center>
                <form id="formCategoria" action="" method="POST">
                    <input type="hidden" id="categoriaId" name="categoriaId">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control form-control-sm" required>
                    </div>
                    <div class="modal__botones-contenedor">
                        <input id="button" type="button" value="Volver" class="btn btn-secondary" onClick="mostrarContenedor2()">
                        <input id="buttonSubmit" type="submit" name="Enviar" class="btn btn-primary">
                        <input type="button" value="Cerrar" class="btn btn-danger" onClick="cerrarModal()">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php if (isset($m)):
    ?>
        <div id="toast"><?= htmlspecialchars($m)
                        ?></div>
    <?php
    endif; ?>
    <script>
        // JavaScript para mostrar el toast
        window.onload = function() {
            var toast = document.getElementById("toast");
            if (toast) {
                toast.className = "show";
                setTimeout(function() {
                    toast.className = toast.className.replace("show", "");
                }, 3000);
            }
        };
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
    <script src="../vista/js/jquery-3.7.1.js"></script>
    <script src="../vista/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dataTables.js"></script>
    <script src="../vista/js/dataTables.bootstrap5.js"></script>
    <script src="../vista/js/tableScript.js"></script>
    <script src="../vista/js/modal_categoria.js"></script>

</body>
</html>