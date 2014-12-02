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
	 * array valores a bindear
	 *
	 * @var unknown
	 */
	private $binds = array ();
	
	/**
	 * array valores de configuracion
	 * 
	 * @var unknown
	 */
	private $user_config = array ();
	
	/**
	 * Constructor de la clase
	 */
	public function __construct() {
		require_once 'app/libs/mysql_DB.php';
		$this->mysqlDB = new MysqlDB ();
	}
	
	/**
	 *
	 * @param unknown $dataForm        	
	 */
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
				
				$this->binds [":$key"] = $value;
			}
			
			// guardamos parametros de configuracion por defecto
			Configuration::porDefecto ();
			$user_config = session::get ( 'config' );
			
			$this->binds ['configuracion'] = json_encode ( $user_config, JSON_UNESCAPED_UNICODE );
			$dataForm ['configuracion'] = '';
			
			$this->mysqlDB->setBinds ( $this->binds );
			$this->mysqlDB->insert ( $this->table, $dataForm );
			unset ( $this->binds );
		}
	}
	
	/**
	 * devuelve listado de usuarios limitados por el offset y el num de registros
	 */
	public function getUsuarios($filtro = NULL, $offset = 0, $count = NULL) {
		
		if (! is_null ( $filtro ) || ! empty ( $filtro ))
			$this->setFiltros ( $filtro );
		
		$this->setFiltros ( $filtro );
		$result = $this->mysqlDB->select ()->limit ( "$offset," . session::get ( 'REGS_PAG' ) )->from ( $this->table )->fetchAll ();
		unset ( $this->binds );
		
		return $result;
	}
	
	/**
	 * Obtiene de la base de datos la información del usuario determinado por su email
	 *
	 * @param unknown $id_usuario        	
	 * @return multitype:
	 */
	public function getUsuarioByEmail($email) {
		// bindeamos parametros
		$binds = array (
				':email' => $email 
		);
		
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( array (
				'email' 
		) )->select ()->from ( $this->table )->fetch ();
		
		return $result;
	}
	
	/**
	 * Obtiene de la base de datos la información del usuario determinado por su id
	 *
	 * @param unknown $id_usuario        	
	 * @return multitype:
	 */
	public function getUsuarioById($id_usuario) {
		// bindeamos parametros
		$binds = array (
				':id_usuario' => $id_usuario 
		);
		
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( array (
				'id_usuario' 
		) )->select ()->from ( $this->table )->fetch ();
		
		return $result;
	}
	public function setFiltros($filtro) {
		// existe filtro campo texto
		if (! empty ( $filtro ['filtro_texto'] )) {
			$this->binds ['username'] = '%' . $filtro ['filtro_texto'] . '%';
			$this->binds ['email'] = '%' . $filtro ['filtro_texto'] . '%';
			
			foreach ( $this->binds as $key => $value ) {
				$fields [$key] = 'like';
			}
			$this->mysqlDB->or_where ( $fields );
			$this->mysqlDB->setBinds ( $this->binds );
		}
	}
	
	/**
	 *
	 * @param unknown $cookie        	
	 * @return string
	 */
	public function getUsuarioCookie($cookie) {
		
		// si no existe cookie
		if (! $cookie) {
			return '';
		}
		
		// comprueba contenido de la cookie y si coincide el hash de la cookie
		list ( $id_usuario, $token, $hash ) = explode ( ':', $cookie );
		if ($hash !== hash ( 'sha256', $id_usuario . ':' . $token )) {
			
			return '';
		}
		
		// si no existe token
		if (empty ( $token )) {
			
			return '';
		}
		
		// obtenemos datos del usuario guardado en la bbdd
		// bindeamos parametros
		$binds = array (
				':id_usuario' => $id_usuario,
				':remember_token' => $token 
		);
		$this->mysqlDB->setBinds ( $binds );
		
		$result = $this->mysqlDB->where ( array (
				'id_usuario',
				'remember_token' 
		) )->select ()->from ( $this->table )->fetch ();
		
		return $result;
	}
	
	/**
     * Edita datos de un usuario determinado por su id
     * 
     * @param unknown $dataForm            
     * @param unknown $id_zona            
     */
    public function editUsuario($dataForm, $id_usuario)
    {
        // bindeamos campos del formulario, que coinciden con la tabla envio
        foreach ($dataForm as $key => $value) {
            $binds[":$key"] = $value;
        }
        // id_envio no está como campo de formulario, lo añadimos
        $binds[":id_usuario"] = $id_usuario;
        
        $this->mysqlDB->setBinds($binds);
        $this->mysqlDB->where('id_usuario')->update($this->table, $dataForm);
    }
	
	/**
	 * guarda los parametros de configuracion de un usuario determinado por su id
	 *
	 * @param unknown $data        	
	 */
	public function setConfigParams($data, $id_usuario) {
		$this->mysqlDB->setBinds ( array (
				':configuracion' => json_encode ( $data, JSON_UNESCAPED_UNICODE ),
				':id_usuario' => $id_usuario 
		) );
		$this->mysqlDB->where ( 'id_usuario' )->update ( $this->table, array (
				'configuracion' => '' 
		) );
		
		// guardamos cambios en la sesion
		foreach ( $data as $key => $value ) {
			session::set ( $key, $value );
		}
		unset ( $this->binds );
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
	
	/**
	 * Obtenemos el numero de filas total de una consulta
	 *
	 * @return Ambigous <>
	 */
	public function getTotalRows($filtro) {
		if (! is_null ( $filtro ) || ! empty ( $filtro ))
			$this->setFiltros ( $filtro );
		
		$result = $this->mysqlDB->select ( "COUNT(*) as total" )->from ( $this->table )->fetch ();
		unset ( $this->binds );
		
		return $result ['total'];
	}
	
	/**
	 * Guarda el token del usuario en la base de datos
	 *
	 * @return string que identificará la cookie
	 */
	public function rememberToken($user) {
		// generate 64 char random string
		$random_token_string = hash ( 'sha256', mt_rand () );
		
		// guardamos token en la tabla usuario
		$this->mysqlDB->setBinds ( array (
				':remember_token' => $random_token_string,
				'id_usuario' => $user ['id_usuario'] 
		) );
		$this->mysqlDB->where ( 'id_usuario' )->update ( $this->table, array (
				'remember_token' => 'remember_token' 
		) );
		// TODO COMPROBAR MYSQLDB FUNCION UPDATE
		
		// generamos cookie que consiste en la id usuario, random string y la combinacion hash de ambos
		$cookie_string_first_part = $user ['id_usuario'] . ':' . $random_token_string;
		$cookie_string_hash = hash ( 'sha256', $cookie_string_first_part );
		
		return $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;
	}
	
	/**
	 * Eliminar la cookie rememberme invalida para evitar un bucle infinito
	 */
	public function deleteCookie() {
		// set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
		// that's obviously the best practice to kill a cookie via php
		// @see http://stackoverflow.com/a/686166/1114320
		setcookie ( 'rememberme', false, time () - (3600 * 3650), '/', NULL );
	}
}