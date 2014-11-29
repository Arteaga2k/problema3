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
     *
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
    public function getUsuario($email)
    {
        // bindeamos parametros
        $binds = array(
            ':email' => $email
        );
        
        $this->mysqlDB->setBinds($binds);
        
        $result = $this->mysqlDB->where(array(
            'email'
        ))
            ->select()
            ->from($this->table)
            ->fetch();
        
        return $result;
    }

    /**
     * 
     * @param unknown $cookie
     * @return string
     */
    public function getUsuarioCookie($cookie)
    {
        
        // si no existe cookie
        if (! $cookie) {
            return '';
        }
        
        // comprueba contenido de la cookie y si coincide el hash de la cookie
        list ($id_usuario, $token, $hash) = explode(':', $cookie);
        if ($hash !== hash('sha256', $id_usuario . ':' . $token)) {
           
            return '';
        }
        
        // si no existe token
        if (empty($token)) {
           
            return '';
        }       
        
        // obtenemos datos del usuario guardado en la bbdd
        // bindeamos parametros
        $binds = array(
            ':id_usuario' => $id_usuario,
            ':remember_token' => $token
        );        
        $this->mysqlDB->setBinds($binds);
        
        $result = $this->mysqlDB->where(array(
            'id_usuario',
            'remember_token'
        ))
            ->select()
            ->from($this->table)
            ->fetch();
        
        return $result;    
    }

    /**
     * Guarda el token del usuario en la base de datos
     *
     * @return string que identificará la cookie
     */
    public function rememberToken($user)
    {
        // generate 64 char random string
        $random_token_string = hash('sha256', mt_rand());        
        
      
        // guardamos token en la tabla usuario        
       $this->mysqlDB->setBinds(array(':remember_token' => $random_token_string, 'id_usuario' => $user['id_usuario']));
       $this->mysqlDB->where('id_usuario')->update($this->table, array('remember_token' => 'remember_token' ));           
       // TODO COMPROBAR MYSQLDB FUNCION UPDATE 
       
        // generamos cookie que consiste en la id usuario, random string y la combinacion hash de ambos
        $cookie_string_first_part = $user['id_usuario'] . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part);
        
        return $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;
    }
    
    /**
     * Eliminar la cookie rememberme invalida para evitar un bucle infinito
     */
    public function deleteCookie()
    {
        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obviously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', NULL);
    }
    
}