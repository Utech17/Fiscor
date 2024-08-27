<?php
// Verifica si la sesión está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

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
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <title>Usuario</title>
    <link rel="icon" type="image/png" href="../vista/img/logo2.png">
</head>
<body>
    <?php imprimirTopBar($usuario); ?>
    <div class="contenedor" style="margin-left: 5px; margin-left: 60px;">
        <div class="barra-lateral">
            <?php imprimirBarraLateral(); ?>
        </div>

        <div class="contenido">
            <h1>Usuario</h1>
        </div>
    </div>

    <div class="container" style="max-width: 1200px; margin-left: 40px; margin-right: 5px;">
        <div class="contenedor-usuario px-6 pt-5">
            <div id="tabla_div" class="table-responsive">
                <?php if ($idRol == 1): ?>
                    <a href="#" class="modal_abrir btn btn-primary" onClick="agregarUsuario();">Agregar Usuario</a>
                    <table id="tabla" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['Usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['Apellido'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php 
                                        if ($usuario['ID_Rol'] == 0) {
                                            echo "Usuario";
                                        } elseif ($usuario['ID_Rol'] == 1) {
                                            echo "Administrador";
                                        } else {
                                            echo "Rol Desconocido";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a onClick="editarUsuario(this)" class="btn-azul" data-id="<?php echo htmlspecialchars($usuario['ID_Usuario'], ENT_QUOTES, 'UTF-8'); ?>" data-usuario="<?php echo htmlspecialchars($usuario['Usuario'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8'); ?>" data-apellido="<?php echo htmlspecialchars($usuario['Apellido'], ENT_QUOTES, 'UTF-8'); ?>" data-rol="<?php echo htmlspecialchars($usuario['ID_Rol'], ENT_QUOTES, 'UTF-8'); ?>"><img src="../vista/img/editar.png" alt="editar"></a>
                                        <a href="?eliminarId=<?php echo htmlspecialchars($usuario['ID_Usuario'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-rojo" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');"><img src="../vista/img/eliminar.png" alt="eliminar"></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <table id="tabla" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Usuario</strong></td>
                                <td><?php echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nombre</strong></td>
                                <td><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Apellido</strong></td>
                                <td><?php echo htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section id="modalUsuario" class="modal_section">
        <div class="modal__contenedor">
            <form id="usuarioForm" action="" method="POST">
                <input type="hidden" id="usuarioId" name="usuarioId">
                <center>
                    <div class="card-header">
                        <h3>Datos de Usuario</h3>
                    </div>
                </center>
                <div class="form-group">
                    <label for="Usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="Apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="Contraseña">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control form-control-sm">
                </div>
                <div class="modal__botones-contenedor">
                    <input type="button" value="Cancelar" class="btn btn-secondary" onClick="cerrarModal()">
                    <input id="buttonSubmit" type="submit" name="Enviar" class="btn btn-primary">
                </div>
            </form>
        </div>
    </section>

    <script src="../vista/js/jquery-3.7.1.js"></script>
    <script src="../vista/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dataTables.js"></script>
    <script src="../vista/js/dataTables.bootstrap5.js"></script>
    <script src="../vista/js/tableScript.js"></script>
    <script src="../vista/js/modal_usuario.js"></script>
</body>
</html>