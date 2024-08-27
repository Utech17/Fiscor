<?php
    require_once("../modelo/conexionPDO.php");

    class PresupuestoModelo extends Conexion {
        private $id_item;
		private $id_proyecto;
        private $cantidad;
        private $monto_presupuesto;
        private $objbd;

        public function __construct(){
			parent::__construct();
			$this->objbd = parent::conectar();
		}
		 
		 // métodos 
		public function get_id(){
			return $this->id_item;
		}
		
		public function set_iditem( $id_item ){
			$this->id_item = $id_item;	
		}

        public function get_idproyecto(){
            return $this->id_proyecto;
        }
        
        public function set_idproyecto( $id_proyecto ){
            $this->id_proyecto = $id_proyecto;
        }

        public function get_cantidad(){
            return $this->cantidad;
        }
        
        public function set_cantidad( $cantidad ){
            $this->cantidad = $cantidad;
        }

        public function get_montopresupuesto(){
            return $this->monto_presupuesto;
        }
        
        public function set_montopresupuesto( $monto_presupuesto ){
            $this->monto_presupuesto = $monto_presupuesto;
        }
	
		public function consultar() {
			$lista = array();
			// Ajusta los nombres de tablas y columnas según tu base de datos
			$registro = "SELECT p.Nombre, SUM(pr.monto_presupuesto) as total_presupuesto
						 FROM presupuesto pr 
						 INNER JOIN proyecto p ON pr.id_proyecto = p.ID_Proyecto
						 GROUP BY p.Nombre";
			
			$preparado = $this->objbd->prepare($registro);
			$resul = $preparado->execute();
		
			// Verificar si la consulta se ejecutó correctamente
			if ($resul) {
				while ($datos = $preparado->fetch(PDO::FETCH_ASSOC)) {
					$lista[] = $datos;
				}
			}
		
			return $lista;
		}
	
		public function modificar(){ 
			$registro= "UPDATE item SET nombre='".$this->nombre."', estado='".$this->estado."' WHERE id_item='".$this->id_item."'";  
			$preparado = $this->objbd->prepare($registro);
			$resul = $preparado->execute();
			return $resul;
		}
		
		public function eliminar() 	{ // funcion para Eliminar
			$registro = "DELETE FROM item WHERE id_item='".$this->id_item."'";
			$preparado = $this->objbd->prepare( $registro );
			$resul = $preparado->execute();
			return $resul;
		}

		public function obtenerCategoriasPorProyecto($id_proyecto) {
			$lista = array();
			$registro = "SELECT c.Nombre as categoria, SUM(p.monto_presupuesto) as presupuesto 
				FROM presupuesto p
				JOIN item i ON p.id_item = i.id_item
				JOIN categoria c ON i.id_categoria = c.ID_Categoria
				WHERE p.id_proyecto = :id_proyecto
				GROUP BY c.Nombre";
			
			$preparado = $this->objbd->prepare($registro);
			$preparado->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
			$preparado->execute();
			
			while ($datos = $preparado->fetch(PDO::FETCH_ASSOC)) {
				$lista[] = $datos;
			}
			
			return $lista;
		}

		public function sumarPresupuestos() {
			$total = 0;
			$registro = "SELECT SUM(monto_presupuesto) as total_presupuesto FROM presupuesto";
			
			$preparado = $this->objbd->prepare($registro);
			$resul = $preparado->execute();
			
			// Verificar si la consulta se ejecutó correctamente
			if ($resul) {
				$datos = $preparado->fetch(PDO::FETCH_ASSOC);
				if ($datos) {
					$total = $datos['total_presupuesto'];
				}
			}
			
			return $total;
		}
    }
?>