<?php

/**
 * Clase enviosmodel
 * 
 * 
 * 
 * @author Carlos
 *
 */
class EnviosModel {
	
	/**
	 * nombre de la tabla envio
	 *
	 * @var unknown
	 */
	private $table = "tbl_envio";
	
	/**
	 * Constructor de la clase
	 */
	public function __construct() {
		require_once 'app/libs/mysql_DB.php';
		$this->mysqlDB = new MysqlDB ();
	}
	
	/**
	 * Devuelve resultado consulta todas las filas de la tabla tbl_envio
	 */
	public function getEnvios(){
		$result = $this->mysqlDB->select ()->from ( $this->table )->fetchAll ();
		
		return $result;
	}
	
	
	/**
	 * Obtener todos los envios de la tabla Envios
	 */
	public function getAllEnvios($filtro = NULL, $offset = 0, $count = NULL) {
		if (is_null ( $filtro )) {
			$result = $this->mysqlDB->select ()->limit ( "$offset," . REGS_PAG )->from ( $this->table )->fetchAll ();
		} else {
			
			$binds = array (
					'direccion' => '%' . $filtro ['filtro_texto'] . '%',
					'poblacion' => '%' . $filtro ['filtro_texto'] . '%',
					'nombre' => '%' . $filtro ['filtro_texto'] . '%',
					'apellido1' => '%' . $filtro ['filtro_texto'] . '%',
					'apellido2' => '%' . $filtro ['filtro_texto'] . '%',
					'razonsocial' => '%' . $filtro ['filtro_texto'] . '%' 
			);
			
			foreach ( $binds as $key => $value ) {
				$fields [$key] = 'like';
			}
			
			if (! empty ( $filtro ['filtro_fec_desde'] )) {
				$binds ['fec_creacion'] = $filtro['filtro_fec_desde'];
			}
			
			if (! empty ( $filtro ['filtro_fec_hasta'] )) {
				$binds ['fec_entrega'] = $filtro['filtro_fec_desde'];
			}
			
			$this->mysqlDB->setBinds ( $binds );
			
			$this->mysqlDB->or_where ( $fields );
			if (isset ( $binds ['fec_creacion'] )) {
				
				$dateField = array (
						'fec_creacion' => '>=' 
				);
				
				$this->mysqlDB->or_where ( $dateField );
			}
			
			if (is_null ( $count )) {
				$this->mysqlDB->select ()->limit ( "$offset," . REGS_PAG )->from ( $this->table )->orderBy ( 'fec_creacion', 'desc' );
				$result = $this->mysqlDB->fetchAll ();
			}else{
				$result = $this->getTotalRows();
			}
			
			// ->where(array('fec_creacion' => '<='))
		}
		
		return $result;
		
		/*
		 * SELECT * FROM `tbl_envio` WHERE nombre
		 * like '%ca%' or telefono1 >=89182192819281 and telefono1 <= 89182192819281
		 * OR telefono2 >= 1 and telefono2 <= 99990000000
		 */
	}
	
	/**
	 * Pide a la base de datos el listado de provincia
	 *
	 * @return array $result Listado de provincias
	 */
	public function getAllProvincias() {
		$result = $this->mysqlDB->select ()->from ( 'tbl_provincia' )->fetchAll ();
		
		return $result;
	}
	
	/**
	 *
	 * @param string $id_envio
	 *        	identificador de la tabla
	 * @return mixed
	 */
	public function getEnvio($id_envio) {
		$binds = array (
				':id_envio' => $id_envio 
		);
		
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( 'id_envio' )->select ()->from ( $this->table )->fetch ();
		
		return $result;
	}
	
	/**
	 *
	 * @param unknown $dataForm        	
	 */
	public function addEnvio($dataForm) {
		
		// bindeamos parametros
		foreach ( $dataForm as $key => $value ) {
			$binds [":$key"] = $value; // iria en el execute
		}
		// var_dump($dataForm);
		$this->mysqlDB->setBinds ( $binds );
		$this->mysqlDB->insert ( $this->table, $dataForm );
	}
	
	/**
	 *
	 * @param unknown $dataForm        	
	 * @param unknown $id_envio        	
	 */
	public function editEnvio($dataForm, $id_envio) {
		// bindeamos campos del formulario, que coinciden con la tabla envio
		foreach ( $dataForm as $key => $value ) {
			$binds [":$key"] = $value;
		}
		// id_envio no está como campo de formulario, lo añadimos
		$binds [":id_envio"] = $id_envio;
		
		$this->mysqlDB->setBinds ( $binds );
		$this->mysqlDB->where ( 'id_envio' )->update ( $this->table, $dataForm );
	}
	public function anotaEnvio($dataForm, $id_envio) {
		// bindeamos campos del formulario, que coinciden con la tabla envio
		foreach ( $dataForm as $key => $value ) {
			$binds [":$key"] = $value;
		}
		// id_envio no está como campo de formulario, lo añadimos
		$binds [":id_envio"] = $id_envio;
		$binds [":estado"] = 'e';
		
		$this->mysqlDB->setBinds ( $binds );
		$this->mysqlDB->where ( 'id_envio' )->update ( $this->table, $dataForm );
	}
	
	/**
	 *
	 * @param unknown $id_envio        	
	 */
	public function deleteEnvio($id_envio) {
		$this->mysqlDB->setBinds ( array (
				':id_envio' => $id_envio 
		) );
		
		$this->mysqlDB->where ( 'id_envio' )->delete ( $this->table );
	}
	
	/**
	 * Obtenemos el numero de filas total de una consulta
	 * @return Ambigous <>
	 */
	public function getTotalRows() {		
		
		$result = $this->mysqlDB->select ( "COUNT(*) as total" )->from ( $this->table )->fetch ();
		
		return $result ['total'];
	}
}