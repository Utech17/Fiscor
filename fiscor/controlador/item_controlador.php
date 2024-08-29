<?php
    require_once("../modelo/item_modelo.php");
    require_once("../modelo/gastos_modelo.php");
    require_once("vista_controlador.php");

    $objItem = new ItemModelo();

    $message = null;
    $idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

    $proyectoExiste = false;
    $categoriaExiste = false;

    // Verificar la existencia del proyecto
    if (isset($_GET['idProyecto'])) {
        $idProyecto = $_GET['idProyecto'];
        $objItem->setID_Proyecto($idProyecto);
        $proyecto = $objItem->buscarProyectoNombreId();
        
        // Verificar si el proyecto fue encontrado
        if ($proyecto) {
            $proyectoExiste = true;
        } else {
            $message = "Proyecto no válido";
            header("Location: ../controlador/proyecto_controlador.php");
            exit();
        }
    }

    // Verificar la existencia de la categoría si el proyecto es válido
    if ($proyectoExiste && isset($_GET['idCategoria'])) {
        $idCategoria = $_GET['idCategoria'];
        $objItem->setID_Categoria($idCategoria);
        $categoria = $objItem->buscarCategoriaNombreId();
        
        // Verificar si la categoría fue encontrada
        if ($categoria) {
            $categoriaExiste = true;
        } else {
            echo "<script>alert('Categoría no válida'); location.href='../controlador/categoria_controlador.php?idProyecto=$idProyecto';</script>";
            exit();
        }
    }

    $data = $objItem->buscarItemsConPresupuesto($idProyecto, $idCategoria);
    $items= $objItem->obtenerItemsPorCategoriaSimple($idCategoria, $idProyecto);
    $dataAux = $objItem->obtenerListaGastos();
    $dataGasto = array(); foreach($dataAux as $c ){
        if( $c['ID_Proyecto'] == $idProyecto ) $dataGasto[ $c['ID_Item'] ][] = $c['Monto_Gasto'];
    }

    // Procesar el formulario de adición de presupuesto
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_item']) && isset($_POST['cantidad']) && isset($_POST['presupuesto'])) {
        $id_item = $_POST['id_item'];
        $cantidad = $_POST['cantidad'];
        $presupuesto = $_POST['presupuesto'];

        // Crear instancia del modelo
        $itemModel = new ItemModelo();
        $itemModel->setID_Item($id_item);
        $itemModel->setCantidad($cantidad);
        $itemModel->setPresupuesto($presupuesto);

        // Asegúrate de obtener el ID del proyecto de alguna forma
        if (isset($idProyecto)) {
            $itemModel->setID_Proyecto($idProyecto);

            // Llamar al método para agregar el presupuesto
            $resultado = $itemModel->agregarPresupuesto();

            if ($resultado) {
                $_SESSION['message'] = 'Elemento agregado exitosamente.';
            } else {
                $_SESSION['message'] = 'Hubo un error al agregar el elemento.';
            }
            header("Location: ../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria");
            exit();
        } else {
            $_SESSION['message'] = 'ID del proyecto no encontrado.';
            header("Location: ../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria");
            exit();
        }
    }

    // Procesar el formulario de adición/modificación de ítem
    if (isset($_POST['Enviar2'])) {
        if (isset($_POST['nombre'], $_POST['estado'], $_POST['cantidad'], $_POST['presupuesto'], $_POST['proyectoId'], $_POST['categoriaId'], $_POST['itemId'])) {

            // Obtener datos del formulario
            $idProyecto = $_POST['proyectoId'];
            $idCategoria = $_POST['categoriaId'];
            $idItem = $_POST['itemId'];

            // Establecer los valores en el objeto Item
            $objItem->setID_Proyecto($idProyecto);
            $objItem->setID_Categoria($idCategoria);
            $objItem->setID_Item($idItem);
            $objItem->setNombre($_POST['nombre']);
            $objItem->setEstado($_POST['estado']);
            $objItem->setCantidad($_POST['cantidad']);
            $objItem->setPresupuesto($_POST['presupuesto']);

            // Agregar o actualizar
            if ($idItem == 0) {
                $resultado = $objItem->agregarItem();
                $mensaje = 'agregado';
            } else if ($idItem > 0) {
                $resultado = $objItem->actualizarItem();
                $mensaje = 'modificado';
            }

            // Verificar resultado de la operación
            if ($resultado == 1) {
                $_SESSION['message'] = "Elemento $mensaje con éxito";
            } else {
                $_SESSION['message'] = "Error al $mensaje item";
            }
            header("Location: ../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria");
            exit();
        } else {
            $_SESSION['message'] = 'Faltan datos para agregar/modificar el item';
            header("Location: ../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria");
            exit();
        }
    }

    // Procesar eliminación de ítem
    if (isset($_POST['eliminarId'])) {
        $objItem->setID_Item($_POST['eliminarId']);
        $resultado = $objItem->eliminarPresupuestoItem($idProyecto);

        if ($resultado) {
            $_SESSION['message'] = 'Elemento eliminado con éxito';
        } else {
            $_SESSION['message'] = 'Este proyecto no puede ser eliminado ya que tiene al menos un gasto asociado. Si hay varios gastos asociados, deberá eliminarlos todos antes de poder eliminar el proyecto y/o su contenido.';
        }
        header("Location: ../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria");
        exit();
    }

    require_once("../vista/item_vista.php");
