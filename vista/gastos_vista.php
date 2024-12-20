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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <title>Gastos</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor">
        <div class="barra-lateral" id="barra-lateral">
            <?php imprimirBarraLateral($idRol); ?>
        </div>
        <div class="contenido">
            <h1>Gastos</h1>
        </div>
    </div>
    <?php if (isset($message)): ?>
        <div id="toast"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="container">
        <div class="contenedor-categoria px-6 pt-5">
            <div class="col-sm-12">
                <a href="#" class="modal_abrir btn btn-primary" onClick="agregarGasto();"> <i class="fa-solid fa-plus"></i> Agregar Gasto</a>
            </div>
            <div id="tabla_div">
                <div class="row">
                    <div class="col-sm-2">
                        <label>Fecha desde</label>
                        <input type="date" class="form-control" id="filtroFechaD" max="<?php echo date('Y-m-d'); ?>" onChange="cambiarFiltro()">
                    </div>
                    <div class="col-sm-2">
                        <label>Fecha hasta</label>
                        <input type="date" class="form-control" id="filtroFechaH" max="<?php echo date('Y-m-d'); ?>" onChange="cambiarFiltro()">
                    </div>
                    <div class="col-sm-2">
                        <label>Proyecto</label>
                        <select id="filtroProyecto" class="form-control form-control-sm" onChange="cambiarFiltroProyecto(this.value)">
                            <option value="0">-- Todos --</option>
                            <?php foreach ($lista_proyectos as $id => $nombre) { ?>
                                <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label>Categoría</label>
                        <select id="filtroCategoria" class="form-control form-control-sm" onChange="cambiarFiltroCategoria(this.value)">
                            <option value="0">-- Todos --</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label>Ítem</label>
                        <select id="filtroItem" class="form-control form-control-sm" onChange="cambiarFiltro()">
                            <option value="0">-- Todos --</option>
                        </select>
                    </div>
                    <br>
                </div>
                <br>
                <table id="tabla" class="table table-bordered table-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Item</th>
                            <th>Monto</th>
                            <th>Proyecto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDataGasto">
                    <?php
                        if (isset($data) && is_array($data)) {
                            foreach ($data as $row) {
                                // Obtener el nombre del proyecto y el ítem
                                $proyecto = isset($lista_proyectos[$row['ID_Proyecto']]) ? $lista_proyectos[$row['ID_Proyecto']] : '';
                                $item = ''; 
                                foreach ($lista_items as $c) {
                                    if ($c['id_item'] == $row['ID_Item']) {
                                        $item = $c['nombre'];
                                        break; // Salir del bucle una vez que encontramos el ítem
                                    }
                                }
                        
                                echo "<tr data-id='" . htmlspecialchars($row['ID_Gasto']) . "' data-fecha='" . htmlspecialchars($row['Fecha']) . "' data-item='" . htmlspecialchars($item) . "' data-monto='" . number_format($row['Monto_Gasto'], 2, '.', ',') . "' data-proyecto='" . htmlspecialchars($proyecto) . "' data-comprobante='" . htmlspecialchars($row['Comprobante']) . "' data-observacion='" . htmlspecialchars($row['Observacion']) . "'>";
                                echo "<td>" . htmlspecialchars($row['Fecha']) . "</td>";
                                echo "<td>" . htmlspecialchars($item) . "</td>";
                                echo "<td>" . number_format($row['Monto_Gasto'], 2, '.', ',') . "</td>"; // Formato decimal para Monto_Gasto
                                echo "<td>" . htmlspecialchars($proyecto) . "</td>";
                                echo "<td class='d-flex justify-content-between' style='width: 100%'>
                                        <a onClick='eliminarGasto(this)' class='btn-rojo' data-id='" . htmlspecialchars($row['ID_Gasto']) . "'><img src='../vista/img/eliminar.png' alt='eliminar'></a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No hay datos disponibles.</td></tr>";
                        }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modales -->
    <section id="modalGasto" class="modal_section modalGasto">
        <div class="modal__contenedor">
            <form id="gastoForm" action="" method="POST">
                <?php if(isset($_GET['ps'])){ ?>
                    <div class="alert alert-danger">
                        <b>Advertencia</b>: Se ha superado el límite de presupuesto. Inicie una auditoría y realice un seguimiento del proyecto para asegurar un control adecuado de los recursos.
                    </div>
                <?php } ?>
                <input type="hidden" id="gastoId" name="gastoId">
                <div class="form-group">
                    <label for="idproyecto">Proyecto</label>
                    <select id="idproyecto" name="idproyecto" class="form-control form-control-sm" onChange="seleccionarProyecto(this.value)" required>
                        <option value="0">-- Selecciona --</option>
                        <?php foreach ($lista_proyectos as $id => $nombre) { ?>
                            <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                    </select>
                </div> 
                <div class="form-group">
                    <label for="idcategoria">Categoría</label>
                    <select id="idcategoria" name="idcategoria" class="form-control form-control-sm" onChange="seleccionarCategoria(this.value)" required>
                        <option value="0">-- Selecciona --</option>
                    </select>
                </div> 
                <div class="form-group">
                    <label for="iditem">Ítem</label>
                    <select id="iditem" name="iditem" class="form-control form-control-sm" required>
                        <option value="0">-- Selecciona --</option>
                    </select>
                </div> 
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control form-control-sm" required
                        max="<?php echo date('Y-m-d'); ?>" 
                        min="<?php echo date('Y-m-d', strtotime('-3 months')); ?>">
                </div>
                <div class="form-group">
                    <label for="montogasto">Monto</label>
                    <input type="text" id="montogasto" name="montogasto" class="form-control form-control-sm" onkeydown="allowOnlyFloat(event)" oninput="validateFloatInput(this)" required>
                </div>
                <div class="form-group">
                    <label for="comprobante">Comprobante</label>
                    <input type="text" id="comprobante" name="comprobante" class="form-control form-control-sm" maxlength="15" required>
                </div>
                <div class="form-group">
                    <label for="observacion">Observación</label>
                    <textarea id="observacion" name="observacion" class="form-control form-control-sm" required></textarea>
                </div>

                <div class="modal__botones-contenedor">
                    <input type="button" value="Cancelar" class="btn btn-secondary" onClick="cerrarModal()">
                    <input id="buttonSubmit" type="submit" name="Enviar" class="btn btn-primary">
                </div>
            </form>
        </div>
    </section>

    <section id="modalEliminar" class="modal_section modalGasto">
        <div class="modal__contenedor">
            <form id="gastoEliminarForm" action="" method="POST">
                <input type="hidden" id="eliminarId" name="eliminarId">
                <b>¿Estás seguro que deseas eliminar el Gasto?</b><br><br>
                Una vez realizado el proceso de eliminación no podrás recuperar el contenido ni la información existente.<br><br>
                <div class="modal__botones-contenedor">
                    <input type="button" value="Cancelar" class="btn btn-secondary" onClick="cerrarModal()">
                    <input id="buttonEliminar" type="submit" name="Confirmar" class="btn btn-primary">
                </div>
            </form>
        </div>
    </section>

    <section class="modal_section modalGasto" id="gastoModal" tabindex="-1" role="dialog" aria-labelledby="gastoModalLabel" aria-hidden="true">
        <div class="modal__contenedor">
            <center>
                <div class="card-header">
                    <h5 class="modal-title" id="gastoModalLabel">Detalles del Gasto</h5>
                </div>
            </center>
            <div class="modal-body">
                <p><strong>Fecha:</strong> <span id="gastoFecha"></span></p>
                <p><strong>Item:</strong> <span id="gastoItem"></span></p>
                <p><strong>Monto:</strong> <span id="gastoMonto"></span></p>
                <p><strong>Proyecto:</strong> <span id="gastoProyecto"></span></p>
                <p><strong>Comprobante:</strong> <span id="gastoComprobante"></span></p>
                <p><strong>Observación:</strong> <span id="gastoObservacion"></span></p>
            </div>
            <div class="modal__botones-contenedor" >
                <input type="button" value="Cerrar" class="btn btn-secondary" onClick="cerrarModal()">
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
        let listaGasto = <?php echo json_encode($data); ?>;
        let listaProyecto = <?php echo json_encode($lista_proyectos); ?>;
        let listaCategoria = <?php echo json_encode($lista_categorias); ?>;
        let listaItem = <?php echo json_encode($lista_items); ?>;
        let listaPresupuesto = <?php echo json_encode($lista_presupuesto); ?>;
    </script>
    <script>
        if( existeParametroGet('modalOn') ){ // Si acaba de agregar, volver a abrir modal
            agregarGasto();
        }
    </script>
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
    <script src="../vista/js/modal_gasto.js"></script>

</body>
</html>