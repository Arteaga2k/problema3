<?php
/**
 * 
 * @author Carlos
 *
 */
class UsuarioModel {
	/**
	 *
	 * @var unknown
	 */
	private $table = "tbl_usuario";
	
	/**
	 * Constructor de la clase
	 */
	public function __construct() {
		require_once 'app/libs/mysql_DB.php';
		$this->mysqlDB = new MysqlDB ();
	}
	public function addUsuario($dataForm) {
	    		
		// crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
		// hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
		// by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
		// how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
		// HASH_COST_FACTOR definido en config.php por defecto en 10
		$hash_cost_factor = (defined ( 'HASH_COST_FACTOR' ) ? HASH_COST_FACTOR : null);
		$user_password_hash = password_hash ( $dataForm ['password_hash'], PASSWORD_DEFAULT, array (
				'cost' => $hash_cost_factor 
		) );
		
		// comprobar si existe usuario con ese username o email
		if ($this->existeEmail ( $dataForm ['email'] ) && $this->existeUsuario ( $dataForm ['username'] )) {
			// bindeamos parametros
			foreach ( $dataForm as $key => $value ) {
				if ($key == 'password_hash')
					$value = $user_password_hash;
				
				$binds [":$key"] = $value;
			}
			var_dump ( $dataForm );
			$this->mysqlDB->setBinds ( $binds );
			$this->mysqlDB->insert ( $this->table, $dataForm );
		}
	}
	
	/**
	 * devuelve listado de usuarios limitados por el offset y el num de registros
	 */
	public function getAllUsuario($offset = 0) {
		$this->mysqlDB->select ()->limit ( "$offset," . REGS_PAG )->from ( $this->table );
		
		return $result = $this->mysqlDB->fetchAll ();
	}
	public function editUsuario() {
	}
	
	/**
	 * comprueba si el nombre de usuario ya existe
	 * 
	 * @return boolean
	 */
	public function existeUsuario($username) {
		$binds = array (
				':username' => $username 
		);
		
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( 'username' )->select ()->from ( $this->table )->fetch ();
		
		return empty ( $result ) ? true : false;
	}
	
	/**
	 * comprueba si email ya está registrado
	 * 
	 * @return boolean
	 */
	public function existeEmail($email) {
		$binds = array (
				':email' => $email 
		);
		
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( 'email' )->select ()->from ( $this->table )->fetch ();
		
		return empty ( $result ) ? true : false;
	}
}