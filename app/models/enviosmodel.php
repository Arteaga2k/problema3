<?php
class EnviosModel extends model {
	
	/**
	 * Obtener todos los envios de la tabla Envios
	 */
	public function getAllEnvios() {
		$sql = "SELECT NOMBRE, APELLIDO1, APELLIDO2, 
				TELEFONO1, TELEFONO2, RAZONSOCIAL, ID_ENVIO,FEC_CREACION, ESTADO
				FROM TBL_ENVIO";
		
		$this->_setSql ( $sql );
		$envios = $this->getAll ();
		
		return $envios;
	}
	
	/**
	 * Obtener listado de provincias
	 */
	public function getAllProvincias() {
		$sql = "SELECT P.ID_PROVINCIA, P.PROVINCIA
				FROM TBL_PROVINCIA P";
		
		$this->_setSql ( $sql );
		$provincias = $this->getAll ();
		
		return $provincias;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::get()
	 */
	public function get($id_envio) {
		// Creamos consulta
		$sql = "SELECT nombre, apellido1, apellido2, direccion,codpostal,email,
				telefono1, telefono2, razonsocial, id_envio,fec_creacion, estado
				FROM TBL_ENVIO
                WHERE ID_ENVIO = :envioID";
		// Asignamos consulta
		$this->_setSql ( $sql );
		
		// bindeamos parámetros
		$params = array (
				':envioID' => $id_envio 
		);
		
		// obtenemos datos del envío
		$result = $this->getFila ( $params );
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::set()
	 */
	public function add() {
		$sql = "INSERT INTO 'kenollega'.'tbl_envio' ('id_envio', 'direccion', 'poblacion', 'cp',
             'provincia', 'email', 'estado', 'fec_creacion', 'fec_entrega', 'observaciones', 'nombre',
             'apellido1', 'apellido2', 'razonsocial', 'telefono1', 'telefono2') 
            VALUES (NULL, 'avenida huelva', 'aljaraque', '21110', '10', 'cav1662@hotmail.com', 'e', '14/10/2014',
             '24/10/2014', 'ninguna', 'carlos', 'arteaga', 'virella', 'onuba sl', '654564564', '5645645')";
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::edit()
	 */
	public function edit($datos) {
		$sql = "UPDATE 'tbl_envio' SET 'direccion'=:direccion,'poblacion'=:poblacion,
    			'codpostal'=:codpostal,'provincia'=:provincia,'email'=:email,'estado'=:estado,
    			'fec_entrega'=:fec_entra,'observaciones'=:observaciones,'nombre'=:nombre,
    			'apellido1'=:apellido1,'apellido2'=:apellido2,'razonsocial'=:razonsocial,
    			'telefono1'=:telefono1,'telefono2'=:telefono2 WHERE 'id_envio'=:id_envio";
		
		// Asignamos consulta
		$this->_setSql ( $sql );
		
		// bindeamos parámetros
		$params = array (
				':envioID' => $datos['id_envio'],
				':direccion' => $datos['direccion'],
				':poblacion' => $datos['poblacion'],
				':codpostal' => $datos['codpostal'],
				':provincia' => $datos['provincia'],
				':email' => $datos['email'],
				':estado' => $datos['estado'],
				':fec_entrega' => $datos['fec_entrega'],
				':observaciones' => $datos['observaciones'],
				':nombre' => $datos['nombre'],
				':apellido1' => $datos['apellido1'],
				':apellido2' => $datos['apellido2']
				
		);
		
		// obtenemos dato del envío
		$result = $this->singleQuery ( $params );
		
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::delete()
	 */
	public function delete($id_envio) {
		$sql = "DELETE FROM tbl_envio WHERE id_envio =  :envioID";
		$this->_setSql ( $sql );
		
		// bindeamos parámetros
		$params = array (
				':envioID' => $id_envio 
		);
		
		// obtenemos dato del envío
		$result = $this->singleQuery ( $params );
		
		return $result;
	}
	
	public function creaSql($array,$accion)
	{
		$columns = array_keys($array);
		$values = array_values($array);
	
		$query = "
		$accion $this->_table (" . implode(", ", $columns) . ")
	       VALUES ('" . implode("', '", $values) . "')";
		
		var_dump($query);
	}
}