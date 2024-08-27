<?php
require_once("../modelo/categoria_modelo.php");
require_once("vista_controlador.php");

$objCategoria = new Categoria();

$proyectoExiste = false;
if (isset($_GET['idProyecto'])) {
    $idProyecto = $_GET['idProyecto'];
    $objCategoria->setID_Proyecto( $idProyecto );
    $proyecto = $objCategoria->buscarProyectoNombreId();
    if( count( $proyecto) > 0 ) $proyectoExiste = true;
}
if( !$proyectoExiste )
    echo "<script>alert('Proyecto no válido'); location.href='../controlador/proyecto_controlador.php';</script>";

$data = $objCategoria->buscarCategoriaPorIDProyecto($idProyecto);
$dataAux = $objCategoria->buscarPresupuestoPorIDProyecto();
$dataPresupuesto = array(); foreach($dataAux as $c ){ $dataPresupuesto[ $c['ID_Categoria'] ][] = $c['monto_presupuesto']; }
$dataAux = $objCategoria->obtenerListaGastos();
$dataGasto = array(); foreach($dataAux as $c ){ $dataGasto[ $c['ID_Categoria'] ][] = $c['Monto_Gasto']; }

// Obtener todas las categorías
$Categorias = $objCategoria->buscarCategoriasNoRelacionadas($idProyecto);

if (isset($_POST['Enviar1'])) {
    if (isset($_POST['id_categoria']) && !empty($_POST['id_categoria'])) {
        $idCategoriaSeleccionada = $_POST['id_categoria'];
        if ($idProyecto !== null) {
            header("Location: ../controlador/item_controlador.php?idCategoria=$idCategoriaSeleccionada&idProyecto=$idProyecto");
            exit();
        } else {
            header("Location:../controlador/categoria_controlador.php?idProyecto=$idProyecto"); 
            exit();
        }
    } else {
        header("Location: ../controlador/proyecto_controlador.php");
        exit();
    }
}

if (isset($_POST['Enviar'])) {
    if (isset($_POST['nombre']) && isset($_POST['estado']) && $idProyecto !== null) {
        $idCategoria = $_POST['categoriaId'] ?? 0;
        $objCategoria->setID_Proyecto($idProyecto);
        $objCategoria->setNombre($_POST['nombre']);
        $objCategoria->setEstado($_POST['estado']);
        
        if ($idCategoria == 0) {
            $resultado = $objCategoria->agregarCategoria();
            $idCategoria = $objCategoria->getLastInsertedID(); 
            $mensaje = 'agregada';
        } else if ($idCategoria > 0) {
            $objCategoria->setID_Categoria($idCategoria);
            $resultado = $objCategoria->actualizarCategoria();
            $mensaje = 'modificada';
        }

        if ($resultado == 1) {
            echo "<script>alert('Categoría $mensaje con éxito'); location.href='../controlador/item_controlador.php?idCategoria=$idCategoria&idProyecto=$idProyecto';</script>";
        } else {
            echo "<script>alert('Error al $mensaje la categoría');</script>";
        }
    } else {
        echo "<script>alert('Faltan datos para agregar la categoría');</script>";
    }
}

if (isset($_GET['eliminarId']) && isset($_GET['idProyecto'])) {
    $idCategoria = $_GET['eliminarId'];
    $idProyecto = $_GET['idProyecto'];

    $resultado = $objCategoria->eliminarPresupuestosPorCategoriaYProyecto($idCategoria, $idProyecto);

    if ($resultado) {
        echo "<script>alert('Categoria eliminada con éxito'); location.href='../controlador/categoria_controlador.php?idProyecto=$idProyecto';</script>";
    } else {
        echo "<script>alert('Error al eliminar categoria');</script>";
    }
}

require_once("../vista/categoria_vista.php");
?>