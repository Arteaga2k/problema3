<?php
class EnviosModel extends model {
	
	/**
	 * Obtener todos los envios de la tabla Envios
	 */
	public function getAllEnvios() {
		$sql = "SELECT D.NOMBRE, D.APELLIDO1, D.APELLIDO2, 
				D.TELEFONO1, D.TELEFONO2, D.RAZONSOCIAL, E.ID_ENVIO, D.ID_DESTINATARIO, E.FEC_CREACION, E.ESTADO
				FROM ENVIO E, DESTINATARIO D
				WHERE E.ID_DESTINATARIO = D.ID_DESTINATARIO";
		
		$this->_setSql ( $sql );
		$envios = $this->getAll ();
		
		return $envios;
	}
	
	/**
	 * Obtener listado de provincias
	 */
	public function getProvincias() {
		$sql = "SELECT P.ID, P.NOMBRE
				FROM PROVINCIA P";
		
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
	public function set() {
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
		$sql = "DELETE FROM envio WHERE id_envio =  :envioID";
		$this->_setSql ( $sql );
		
		// bindeamos parámetros
		$params = array (
				':envioID' => $id_envio 
		);
		
		$result = $this->singleQuery ( $params );
		
		return $result;
	}
}