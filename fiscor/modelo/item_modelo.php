<?php
    require_once("../modelo/conexionPDO.php");

    class ItemModelo extends Conexion {
        private $id_item;
		private $id_categoria;
		private $id_proyecto;
        private $nombre;
        private $estado;
		private $cantidad;
        private $presupuesto;
        private $objbd;

        public function __construct(){
			parent::__construct();
			$this->conexion = parent::conectar();
		}

		 // métodos 
		public function getID_Item(){
			return $this->id_item;
		}

		public function setID_Item( $id_item ){
			$this->id_item = $id_item;	
		}

		public function getID_Categoria(){
			return $this->id_categoria;
		}

		public function setID_Categoria( $id_categoria ){
			$this->id_categoria = $id_categoria;
		}

		public function getID_Proyecto(){
			return $this->id_proyecto;
		}

		public function setID_Proyecto( $id_proyecto ){
			$this->id_proyecto = $id_proyecto;
		}

		public function getNombre(){
			return $this->nombre;
		}

		public function setNombre( $nombre ){
			$this->nombre = $nombre;
		}

		public function getEstado(){
			return $this->estado;
		}

		public function setEstado( $estado ){
			$this->estado = $estado;
		}

		public function getCantidad(){
			return $this->cantidad;
		}

		public function setCantidad( $cantidad ){
			$this->cantidad = $cantidad;
		}

		public function getPresupuesto(){
			return $this->presupuesto;
		}

		public function setPresupuesto( $presupuesto ){
			$this->presupuesto = $presupuesto;
		}

		public function agregarItem() { 
			$registro = "INSERT INTO item (id_item, ID_Categoria, nombre, estado) 
						 VALUES (:id_item,:ID_Categoria,:nombre,:estado)";
			$preparado = $this->conexion->prepare($registro);
			$preparado->bindParam(':id_item', $this->id_item);
			$preparado->bindParam(':ID_Categoria', $this->id_categoria);
			$preparado->bindParam(':nombre', $this->nombre);
			$preparado->bindParam(':estado', $this->estado);
			$resul= $preparado->execute();
			if( $resul ){
				$this->id_item = $this->conexion->lastInsertId();
				$result2 = $this->agregarPresupuesto();
				if( $result2 )
					$res = 1;
				else {
					$this->eliminarItem(1);
					$res = 0;
				}
			} else
				$res = 0;
			return $res;
		}

		public function agregarPresupuesto() { 
			$registro = "INSERT INTO presupuesto (id_item, id_proyecto, cantidad, monto_presupuesto) 
						 VALUES (:id_item,:id_proyecto,:cantidad,:presupuesto)";
			$preparado = $this->conexion->prepare($registro);
			$preparado->bindParam(':id_item', $this->id_item);
			$preparado->bindParam(':id_proyecto', $this->id_proyecto);
			$preparado->bindParam(':cantidad', $this->cantidad);
			$preparado->bindParam(':presupuesto', $this->presupuesto);
			$resul= $preparado->execute();
			if( $resul )
				$res = 1;
			else
				$res = 0;
			return $res;
		}

		public function buscarProyectoNombreId(){
			$res = array();
			$registro="SELECT nombre 
					   from proyecto 
					   where ID_Proyecto ='".$this->id_proyecto."' LIMIT 1";
			$preparado = $this->conexion->prepare($registro);
			$preparado->execute();
			$datos = $preparado->fetch(PDO::FETCH_ASSOC);
			if( $datos ){
				$res['nombre'] = $datos['nombre'];
			}
			return $res;
		}

		public function buscarCategoriaNombreId(){
			$res = array();
			$registro="SELECT nombre 
					   from categoria 
					   where ID_Categoria ='".$this->id_categoria."' LIMIT 1";
			$preparado = $this->conexion->prepare($registro);
			$preparado->execute();
			$datos = $preparado->fetch(PDO::FETCH_ASSOC);
			if( $datos){
				$res['nombre'] = $datos['nombre'];
			}
			return $res;
		}


		public function buscarItemsConPresupuesto($idProyecto, $idCategoria) {
			try {
				$sql = "SELECT i.*, p.cantidad, p.monto_presupuesto 
						FROM item i
						JOIN presupuesto p ON i.id_item = p.id_item 
						WHERE i.id_categoria = :idCategoria AND p.id_proyecto = :idProyecto";
				$stmt = $this->conexion->prepare($sql);
				$stmt->bindParam(':idCategoria', $idCategoria);
				$stmt->bindParam(':idProyecto', $idProyecto);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				error_log("Error al buscar ítems con presupuesto por ID de proyecto y categoría: " . $e->getMessage(), 0);
				return array();
			}
		}

		public function actualizarItem() {
			try {
				$sql = "UPDATE item 
						SET nombre = :nombre, estado = :estado 
						WHERE id_item = :id_item;
						UPDATE presupuesto 
						SET cantidad = :cantidad, monto_presupuesto = :presupuesto 
						WHERE id_item = :id_item AND id_proyecto = :id_proyecto";
				$stmt = $this->conexion->prepare($sql);
				$stmt->bindParam(':nombre', $this->nombre);
				$stmt->bindParam(':estado', $this->estado);
				$stmt->bindParam(':id_item', $this->id_item);
				$stmt->bindParam(':cantidad', $this->cantidad);
				$stmt->bindParam(':presupuesto', $this->presupuesto);
				$stmt->bindParam(':id_proyecto', $this->id_proyecto);
		
				return $stmt->execute() ? 1 : 0;
			} catch (PDOException $e) {
				error_log("Error al actualizar el ítem: " . $e->getMessage(), 0);
				return 0;
			}
		}

		public function eliminarPresupuestoItem($idProyecto) {
			try {
				// Verificar si existen registros en la tabla gasto que estén relacionados con el ítem y proyecto
				$sqlCheck = "SELECT COUNT(*) 
							 FROM gasto 
							 WHERE ID_Item = :id_item AND ID_Proyecto = :id_proyecto";
				$stmtCheck = $this->conexion->prepare($sqlCheck);
				$stmtCheck->bindParam(':id_item', $this->id_item);
				$stmtCheck->bindParam(':id_proyecto', $idProyecto);
				$stmtCheck->execute();
				$count = $stmtCheck->fetchColumn();
		
				if ($count > 0) {
					// Si existen registros relacionados en gasto, no se puede eliminar el presupuesto
					error_log("No se puede eliminar el presupuesto porque existen registros relacionados en gasto.", 0);
					return false;
				}
		
				// Si no existen registros en gasto, proceder con la eliminación
				$sqlDelete = "DELETE 
							  FROM presupuesto 
							  WHERE id_item = :id_item AND id_proyecto = :id_proyecto";
				$stmtDelete = $this->conexion->prepare($sqlDelete);
				$stmtDelete->bindParam(':id_item', $this->id_item);
				$stmtDelete->bindParam(':id_proyecto', $idProyecto);
				$result = $stmtDelete->execute();
		
				return $result;
			} catch (PDOException $e) {
				error_log("Error al eliminar el presupuesto del ítem: " . $e->getMessage(), 0);
				return false;
			}
		}

		public function obtenerItemsPorCategoriaSimple($idCategoria, $idProyecto) {
			try {
				$sql = "SELECT id_item, nombre, estado 
						FROM item 
						WHERE id_categoria = :idCategoria
						AND id_item NOT IN (
							SELECT id_item 
							FROM presupuesto 
							WHERE id_proyecto = :idProyecto
						)";
				$stmt = $this->conexion->prepare($sql);
				$stmt->bindParam(':idCategoria', $idCategoria);
				$stmt->bindParam(':idProyecto', $idProyecto);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				error_log("Error al obtener ítems por categoría: " . $e->getMessage(), 0);
				return array();
			}
		}

		public function obtenerListaGastos() {
			try {
				$sql = "SELECT * FROM gasto";
				$stmt = $this->conexion->prepare($sql);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				error_log("Error al buscar todos los gastos: " . $e->getMessage(), 0);
				return array();
			}
		}
	}
?>