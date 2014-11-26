<?php

/**
 * Clase loginmodel
 * 
 * 
 * 
 * @author Carlos
 */
class LoginModel
{
	/**
	 * variable con el nombre de la tabla
	 * @var unknown
	 */
	private $table = "tbl_usuario";

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require_once 'app/libs/mysql_DB.php';
        $this->mysqlDB = new MysqlDB();
    }

    /**
     * Pide a la base de datos la información del usuario que intenta hacer login
     * 
     * @param unknown $id_usuario            
     * @return multitype:
     */
    public function loginUsuario($email)
    {
     // bindeamos parametros
    	$binds = array (
    			':email' => $email
    	);
        
        $this->mysqlDB->setBinds($binds);
        
        $result = $this->mysqlDB->where(array('email'))
            ->select()
            ->from($this->table)
            ->fetch();
        
        return $result;
    }
    
    
    public function loginconCookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    
        // si no existe cookie
        if (!$cookie) {
            $_SESSION["session_error"][] = 'COOKIE INVALIDA';
            return false;
        }
    
        // comprueba contenido de la cookie y si coincide el hash de la cookie
        list ($user_id, $token, $hash) = explode(':', $cookie);
        if ($hash !== hash('sha256', $user_id . ':' . $token)) {
            $_SESSION["session_error"][] = 'COOKIE INVALIDA';
            return false;
        }
    
        // si no existe token
        if (empty($token)) {
            $_SESSION["session_error"][] = 'COOKIE INVALIDA';
            return false;
        }
    
        // obtener token y datos del usuario
        // $data = select * usuario where user_remember_token = :user_remember_token
       
       /* if ($data == 1) {            
            // TODO: Escribir un metodo para setear datos sesion, hace lo mismo q login
            Session::init();
            Session::set('user_logged_in', true);
            Session::set('user_id', $result->user_id);
            Session::set('user_name', $result->user_name);
            Session::set('user_email', $result->user_email);
            Session::set('user_account_type', $result->user_account_type);
            Session::set('user_provider_type', 'DEFAULT');
            Session::set('user_avatar_file', $this->getUserAvatarFilePath());    
            
            $_SESSION["session_ok"][] = 'LOGIN CON COOKIE OK';
            return true;
        } else {
            $_SESSION["session_error"][] = 'COOKIE INVALIDA';
            return false;
        }*/
    }

    /**
     * Guarda el token del usuario en la base de datos
     *
     * @return string que identificará la cookie
     */
    public function rememberToken($datos)
    {
        // generate 64 char random string
        $random_token_string = hash('sha256', mt_rand());
        
        // guardamos token en la tabla usuario
        // update usuario set user_rememberme_token...
        
        //TODO DEVOLVER USUARIO ID mediante email and pwd
        
        // generate cookie string that consists of user id, random string and combined hash of both
        $cookie_string_first_part = $result->user_id . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part);
        return $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;
    }

   
}