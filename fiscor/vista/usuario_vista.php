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
$m = null;
if (isset($message)) {
    $_SESSION['message'] = $message;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Recuperar el mensaje de la sesión y luego eliminarlo
$m = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);

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
    <div class="contenedor">
        <div class="barra-lateral"  id="barra-lateral">
            <?php imprimirBarraLateral($idRol); ?>
        </div>

        <div class="contenido">
            <h1>Usuario</h1>
        </div>
    </div>
    <?php if (isset($message)): ?>
        <div id="toast"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="container">
        <div class="contenedor-usuario px-6 pt-5">
            <div id="tabla_div" class="table-responsive">
                <?php if ($idRol == 1): ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="#" class="modal_abrir btn btn-primary" onClick="agregarUsuario();">Agregar Usuario</a>
                        </div>
                        <div class="table-container">    
                            <table id="tabla" class="table table-bordered table-responsive" style="width:100%">
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
                        </div>
                    </div>
                <?php else: ?>
                <div class="row">
                    <div class="table-container">
                        <table id="tabla" class="table table-bordered table-responsive" style="width:100%">
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
                    </div>
                </div>
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
    <script src="../vista/js/modal_usuario.js"></script>
    <script src="../vista/js/modal_notificacion.js"></script>
    
</body>
</html>