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
$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : '0';

if (isset($_GET['Volver'])) {
    session_destroy();
    echo "<script>
        location.href='../index.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vista/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <title>Elementos</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor">
        <div class="barra-lateral" id="barra-lateral">
            <?php imprimirBarraLateral($idRol); ?>
        </div> 
        <div class="contenido">
            <h1> <a href="proyecto_controlador.php"><?php echo $proyecto['nombre'] ?></a>
                > <a href="categoria_controlador.php?idProyecto=<?php echo $idProyecto ?>"><?php echo $categoria['nombre'] ?></a>
                > Elementos</h1>
        </div>
    </div>
    <div class="container">
        <div class="contenedor-categoria px-6 pt-5">
            <div id="tabla_div">
                <div class="row">
                    <div class="col-sm-3">
                        <a href="#" class="modal_abrir btn btn-primary" onClick="agregarItem();">Agregar Elemento</a>
                    </div>
                <div class="table-container">
                        <table id="tabla" class="table table-striped" style="width:100%">
                            <thead>
                                <th>Estado</th>
                                <th>Elemento</th>
                                <th>Cantidad</th>
                                <th>Presupuesto</th>
                                <th>Monto Gastado</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($data) && is_array($data)) {
                                        foreach ($data as $row) {
                                            $row['monto_gastado'] = 0.00; if( isset( $dataGasto[$row['id_item']] )) $row['monto_gastado'] = array_sum( $dataGasto[$row['id_item']] );
                                            $claseGastado = $row['monto_gastado'] > $row['monto_presupuesto'] ? 'bg-danger text-white' : 'bg-success text-white';

                                            echo "<tr>";
                                            echo "<td>" . ($row['estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . number_format( $row['monto_presupuesto'], 2) . "</td>";
                                            echo "<td class='$claseGastado'>". number_format( $row['monto_gastado'], 2) ."</td>";
                                            echo "<td>
                                                    <a onClick='buscarItem(this)' class='btn-azul' data-id='".$row['id_item']."' data-nombre='".$row['nombre']."' data-estado='".$row['estado']."' data-cantidad='".$row['cantidad']."' data-presupuesto='".$row['monto_presupuesto']."'><img src='../vista/img/editar.png' alt='editar'></a>
                                                    <a href='?eliminarId=".$row['id_item']."&idProyecto=".$idProyecto."&idCategoria=".$idCategoria."' class='btn-rojo'><img src='../vista/img/eliminar.png' alt='eliminar'></a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No hay datos disponibles.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>    
                </div>
            </div>
        </div>
    </div>

    <section id="modalItem" class="modal_section">
        <div class="modal__contenedor">
            <!-- Contenedor de Seleccionar Ítem -->
            <div id="contenedor1" class="modal__content" style="display: none;">
                <center>
                    <div class="card-header">
                        <h3>Seleccionar Elemento</h3>
                    </div>
                </center>
                <form id="selectItemForm" action="" method="POST">
                    <div class="form-group">
                        <label for="seleccionarItem">Seleccionar Elemento</label>
                        <select id="seleccionarItem" class="form-control" name="id_item" onchange="mostrarCamposPresupuesto()">
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['id_item']; ?>">
                                    <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Campos ocultos por defecto que aparecerán después de seleccionar un ítem -->
                    <div id="camposPresupuesto" style="display: none;">
                        <div class="form-group">
                            <label for="cantidadItem">Cantidad</label>
                            <input type="number" id="cantidadItem" class="form-control form-control-sm" maxlength="5" min="0" max="99999" name="cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="presupuestoItem">Presupuesto</label>
                            <input type="number" id="presupuestoItem" class="form-control form-control-sm" name="presupuesto" onkeydown="allowOnlyFloat(event)" oninput="validateFloatInput(this)" required>
                        </div>
                    </div>

                    <div class="modal__botones-contenedor">
                        <input type="button" value="Agregar nuevo elemento" class="btn btn-secondary" onClick="mostrarContenedor2()">
                        <input type="submit" value="Enviar" name="Enviar1" class="btn btn-primary">
                        <input type="button" value="Cerrar" class="btn btn-danger" onClick="cerrarModal()">
                    </div>
                </form>
            </div>
            <!-- Contenedor de Agregar Ítem -->
            <div id="contenedor2" class="modal__content" style="display: none;">
                <form id="itemForm" action="" method="POST">
                    <input type="hidden" id="itemId" name="itemId" value="0">
                    <input type="hidden" id="categoriaId" name="categoriaId" value="<?php echo $idCategoria ?>">
                    <input type="hidden" id="proyectoId" name="proyectoId" value="<?php echo $idProyecto ?>">
                    <center>
                        <div class="card-header">
                            <h3>Datos del Elemento</h3>
                        </div>
                    </center>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="Nombre">Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control form-control-sm" maxlength="5" min="0" max="99999" required>
                    </div>
                    <div class="form-group">
                        <label for="Nombre">Presupuesto</label>
                        <input type="text" id="presupuesto" name="presupuesto" class="form-control form-control-sm" onkeydown="allowOnlyFloat(event)" oninput="validateFloatInput(this)" required>
                    </div>
                    <div class="modal__botones-contenedor">
                        <input type="button" id="button" value="Volver" class="btn btn-secondary" onClick="mostrarContenedor1()">
                        <input type="submit" id="buttonSubmit" value="Enviar" name="Enviar2" class="btn btn-primary">
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
    <script src="../vista/js/modal_item.js"></script>
</body>

</html>