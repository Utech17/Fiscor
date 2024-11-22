<?php
    require_once("../modelo/categoria_modelo.php");
    require_once("vista_controlador.php");
    $message = null;
    $objCategoria = new Categoria();

    $proyectoExiste = false;
    if (isset($_GET['idProyecto'])) {
        $idProyecto = $_GET['idProyecto'];
        $objCategoria->setID_Proyecto($idProyecto);
        $proyecto = $objCategoria->buscarProyectoNombreId();
        
        // Verificar si el proyecto fue encontrado
        if ($proyecto) { 
            $proyectoExiste = true;
        }
    }

    // Si el proyecto no existe, redirigir al controlador de proyectos
    if (!$proyectoExiste) {
        $message = "Proyecto no válido"; 
        header("Location: ../controlador/proyecto_controlador.php");
        exit();
    }

    $data = $objCategoria->buscarCategoriaPorIDProyecto($idProyecto);
    $categorias = isset($data['categorias']) ? $data['categorias'] : [];
    $estadoProyecto = isset($data['estado_proyecto']) ? $data['estado_proyecto'] : null;

    $dataAux = $objCategoria->buscarPresupuestoPorIDProyecto();
    $dataPresupuesto = array(); foreach($dataAux as $c ){ $dataPresupuesto[ $c['id_categoria'] ][] = $c['monto_presupuesto']; }
    $dataAux = $objCategoria->obtenerListaGastos();
    $dataGasto = array(); foreach($dataAux as $c ){ $dataGasto[ $c['ID_Categoria'] ][] = $c['Monto_Gasto']; }


    // Obtener todas las categorías
    $Categorias = $objCategoria->buscarCategoriasNoRelacionadas($idProyecto);

    if (isset($_POST['Enviar1'])) {
        if (isset($_POST['id_categoria']) && !empty($_POST['id_categoria'])) {
            $idCategoriaSeleccionada = $_POST['id_categoria'];
            
            if ($idProyecto !== null) {
                // Establecer mensaje de éxito en sesión
                $_SESSION['message'] = 'Categoría agregada con éxito. Ahora puedes agregar elementos a esta categoría.';
                
                // Redirigir a item_controlador.php con los parámetros necesarios
                header("Location: ../controlador/item_controlador.php?idCategoria=$idCategoriaSeleccionada&idProyecto=$idProyecto");
                exit();
            } else {
                header("Location: ../controlador/categoria_controlador.php?idProyecto=$idProyecto");
                exit();
            }
        } else {
            header("Location: ../controlador/proyecto_controlador.php");
            exit();
        }
    }

    if (isset($_POST['Enviar'])) {
        if (!empty($_POST['nombre']) && isset($_POST['estado']) && $idProyecto != 0) {
            // Obtener ID de categoría (si existe) o establecerlo en 0
            $idCategoria = $_POST['categoriaId'] ?? 0;

            // Establecer los datos de la categoría
            $objCategoria->setID_Proyecto($idProyecto);
            $objCategoria->setNombre($_POST['nombre']);
            $objCategoria->setEstado($_POST['estado']);

            // Verificar si estamos agregando o actualizando
            if ($idCategoria == 0) {
                // Agregar categoría
                $resultado = $objCategoria->agregarCategoria();
                $idCategoria = $objCategoria->getLastInsertedID();
                $mensaje = 'agregada';

                // Verificar el resultado de la adición y redirigir si tuvo éxito
                if ($resultado == 1) {
                    $_SESSION['message'] = "Categoría $mensaje con éxito. Ahora puedes agregar elementos a esta categoría.";
                    header("Location: ../controlador/item_controlador.php?idCategoria=$idCategoria&idProyecto=$idProyecto");
                    exit();
                } else {
                    $_SESSION['message'] = "Error al $mensaje la categoría";
                    // Puedes redirigir a la misma página para mostrar el mensaje de error, si lo prefieres.
                    header("Location: ../controlador/categoria_controlador.php?idProyecto=$idProyecto");
                    exit();
                }
            } else {
                // Actualizar categoría
                $objCategoria->setID_Categoria($idCategoria);
                $resultado = $objCategoria->actualizarCategoria();
                $mensaje = 'modificada';

                // Verificar el resultado de la actualización pero no redirigir
                if ($resultado == 1) {
                    $_SESSION['message'] = "Categoría $mensaje con éxito";
                } else {
                    $_SESSION['message'] = "Error al $mensaje la categoría";
                }
                
                // Redirigir a la misma página para mostrar el mensaje
                header("Location: ../controlador/categoria_controlador.php?idProyecto=$idProyecto");
                exit();
            }
        }
    }

    if (isset($_GET['eliminarId']) && isset($_GET['idProyecto'])) {
        
        $message = "No se puede eliminar la categoría porque tiene elementos asociados. Debe eliminar los elementos asociados primero.";
        
        // Redirigir después de establecer el mensaje
        $_SESSION['message'] = $message;
        header("Location: " . $_SERVER['PHP_SELF'] . "?idProyecto=" . $idProyecto);
        exit();
    }

    require_once("../vista/categoria_vista.php");
