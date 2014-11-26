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
	
	public function addZona(){
		
	}
	
	public function editZona(){
		
	}
	
	public function deleteZona(){
		
	}
}