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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vista/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <title>Proyecto</title>
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor" style="margin-left: 5px; margin-left: 60px;">
        <div class="barra-lateral">
            <?php imprimirBarraLateral(); ?>
        </div>

        <div class="contenido">
            <h1>Proyecto</h1>
        </div>
    </div>

    <div class="container" style="max-width: 1200px; margin-left: 40px; margin-right: 5px;">
        <div class="contenedor-categoria px-6 pt-5">
            <div id="tabla_div">
                <div class="row">
                    <div class="col-sm-3">
                        <a href="#" class="modal_abrir btn btn-primary" onClick="agregarProyecto();">Agregar Proyecto</a>
                    </div>
                    <table id="tabla" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Presupuesto</th>
                                <th>Monto Gastado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data) && is_array($data)) {
                                foreach ($data as $row) {
                                    $row['monto_presupuesto'] = 0.00; if( isset( $dataPresupuesto[$row['ID_Proyecto']] )) $row['monto_presupuesto'] = array_sum( $dataPresupuesto[$row['ID_Proyecto']] );
                                    $row['monto_gastado'] = 0.00; if( isset( $dataGasto[$row['ID_Proyecto']] )) $row['monto_gastado'] = array_sum( $dataGasto[$row['ID_Proyecto']] );
                                    $claseGastado = $row['monto_gastado'] > $row['monto_presupuesto'] ? 'bg-danger text-white' : 'bg-success text-white';
                                    echo "<tr>";
                                        $estado = ($row['Estado'] == 1) ? 'Activo' : 'Inactivo';
                                        echo "<td>" . $estado . "</td>";
                                        echo "<td>" . $row['Nombre'] . "</td>";
                                        echo "<td>" . $row['Descripcion'] . "</td>";
                                        echo "<td>". number_format( $row['monto_presupuesto'], 2) ."</td>";
                                        echo "<td class='$claseGastado'>". number_format( $row['monto_gastado'], 2) ."</td>";
                                        echo "<td>
                                            <a href='../controlador/categoria_controlador.php?idProyecto=".$row['ID_Proyecto']."' class='btn-azul'><img src='../vista/img/ojo.png' alt='ojo'></a>
                                            <a onClick='buscarProyecto(this)' class='btn-azul' data-id='".$row['ID_Proyecto']."' data-estado='".$row['Estado']."' data-nombre='".$row['Nombre']."' data-descripcion='".$row['Descripcion']."'><img src='../vista/img/editar.png' alt='editar'></a>
                                            <a href='?eliminarId=".$row['ID_Proyecto']."' class='btn-rojo'><img src='../vista/img/eliminar.png' alt='eliminar'></a>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
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

    <section id="modalProyecto" class="modal_section">
        <div class="modal__contenedor">
            <form id="proyectoForm" action="" method="POST">
                <input type="hidden" id="proyectoId" name="proyectoId">
                <center>
                    <div class="card-header ">
                        <h3>Datos de Proyecto</h3>
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
                    <input type="text" id="nombre" name="nombre" class="form-control form-control-sm" required value="">
                </div>
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control form-control-sm" value=""></textarea>
                </div>

                <div class="modal__botones-contenedor">
                    <input type="button" value="Cancelar" class="btn btn-secondary" onClick="cerrarModal()">
                    <input id="buttonSubmit" type="submit" name="Enviar" class="btn btn-primary">
                </div>
            </form>

        </div>
    </section>

    </div>
    <script src="../vista/js/jquery-3.7.1.js"></script>
    <script src="../vista/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dataTables.js"></script>
    <script src="../vista/js/dataTables.bootstrap5.js"></script>
    <script src="../vista/js/tableScript.js"></script>
    <script src="../vista/js/modal_proyecto.js"></script>
</body>
</html>