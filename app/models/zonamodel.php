<?php
/**
 * CLASE zonamodel
 * @author 2DAWT
 *
 */
class ZonaModel {
	
	/**
	 * Constructor de la clase
	 */
	public function __construct() {
		require_once 'app/libs/mysql_DB.php';
		$this->mysqlDB = new MysqlDB ();
	}
	
	/**
	 * Pide a la  base de datos el listado de zonas
	 * 
	 * @return multitype:
	 */
	public function getAllZonas() {
		$result = $this->mysqlDB->select()
		->from('tbl_zona')
		->fetchAll();
		
		 
		return $result;
	}
	
	/**
	 * 
	 */
	public function addZona(){
		// bindeamos parametros
		foreach ( $dataForm as $key => $value ) {
			$binds [":$key"] = $value; // iria en el execute
		}
		// var_dump($dataForm);
		$this->mysqlDB->setBinds ( $binds );
		$this->mysqlDB->insert ( $this->table, $dataForm );
		
	}
	
	public function editZona(){
		
	}
	
	public function deleteZona(){
		
	}
}