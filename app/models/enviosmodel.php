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
	public function getProvincias() {
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
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::set()
	 */
	public function add() {
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Model::edit()
	 */
	public function edit($id_envio) {
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
		
		$result = $this->singleQuery ( $params );
		
		return $result;
	}
}