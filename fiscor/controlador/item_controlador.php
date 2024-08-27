<?php
    require_once("../modelo/item_modelo.php");
    require_once("vista_controlador.php");

    $objItem = new ItemModelo();

    $proyectoExiste = false;
    if (isset($_GET['idProyecto'])) {
        $idProyecto = $_GET['idProyecto'];
        $objItem->setID_Proyecto( $idProyecto );
        $proyecto = $objItem->buscarProyectoNombreId();
        if( count( $proyecto) > 0 ) $proyectoExiste = true;
    }
    if( !$proyectoExiste )
        echo "<script>alert('Proyecto no válido'); location.href='proyecto_controlador.php';</script>";

    $categoriaExiste = false;
    if (isset($_GET['idCategoria'])) {
        $idCategoria = $_GET['idCategoria'];
        $objItem->setID_Categoria( $idCategoria );
        $categoria = $objItem->buscarCategoriaNombreId();
        if( count( $categoria ) > 0 ) $categoriaExiste = true;
    }
    if( !$categoriaExiste )
        echo "<script>alert('Categoría no válida'); location.href='../controlador/categoria_controlador.php?idProyecto=$idProyecto';</script>";

    $data = $objItem->buscarItemsConPresupuesto($idProyecto, $idCategoria);
    $items= $objItem->obtenerItemsPorCategoriaSimple($idCategoria, $idProyecto);
    $dataAux = $objItem->obtenerListaGastos();
    $dataGasto = array(); foreach($dataAux as $c ){
        if( $c['ID_Proyecto'] == $idProyecto ) $dataGasto[ $c['ID_Item'] ][] = $c['Monto_Gasto'];
    }

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
                echo "<script>alert('Elemento agregado exitosamente.'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
            } else {
                echo "<script>alert('Hubo un error al agregar el elemento.'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
            }
        } else {
            echo "<script>alert('ID del proyecto no encontrado.'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
        }
    }

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
            } else if ($idItem > 0) {
                $resultado = $objItem->actualizarItem();
            }

            // Verificar resultado de la operación
            if ($resultado == 1) {
                if ($idItem == 0) {
                    echo "<script>alert('Elemento agregado con éxito'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
                } else {
                    echo "<script>alert('Elemento modificado con éxito'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
                }
            } else {
                echo "<script>alert('Error al " . ($idItem == 0 ? "agregar" : "modificar") . " item');</script>";
            }
        } else {
            echo "<script>alert('Faltan datos para agregar/modificar el item');</script>";
        }
    }

    if (isset($_GET['eliminarId'])) {
        $objItem->setID_Item($_GET['eliminarId']);
        $resultado = $objItem->eliminarPresupuestoItem($idProyecto);

        if( $resultado )
            echo "<script>alert('Elemento eliminado con éxito'); location.href='../controlador/item_controlador.php?idProyecto=$idProyecto&idCategoria=$idCategoria';</script>";
        else
            echo "<script>alert('Error al eliminar Elemento');</script>";
    }

    require_once("../vista/item_vista.php");
?>