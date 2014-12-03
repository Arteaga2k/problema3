<?php

/**
 * Clase instaladormodel
 *
 *
 *
 * @author Carlos
 *
 */
class InstaladorModel
{
    private $DB_TYPE = null;
    private $DB_HOST = null;
    private $DB_NAME = null;
    private $DB_USER = null;
    private $DB_PASS = null;

    public function compruebaConexion($data)
    {
        
        /**
         * Configuración: Parámetros de la base de datos
         * Contraseña, tipo, usuario...etc
         */
        $this->DB_TYPE = 'mysql';
        $this->DB_HOST = $data['DB_HOST'];
        $this->DB_NAME = $data['DB_NAME'];
        $this->DB_USER = $data['DB_USER'];
        $this->DB_PASS = $data['DB_PASS'];
        
        try {
            $dsn = $this->DB_TYPE . ':host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, $this->DB_USER, $this->DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $this->generaConfig();
            $sql = file_get_contents('app/install/install.sql');            
            $qr = $db->exec($sql);               
            
        } catch (PDOException $e) {           
            header('location: ' . URL . 'instalador/error');
        }
        
        header('location: ' . URL);
      
    }
    
    /**
     * Generamos documento config.php
     */
    public function generaConfig(){
        $fichero = 'app/config.php';
        $cadena = "<?php
        /**
        * Configuración: La URL del proyecto problema1
        */
        define('URL', 'http://localhost/problema3/');
        
        
        
        /**
        * Configuración: Parámetros de la base de datos
        * Contraseña, tipo, usuario...etc
        */
        define('DB_TYPE', '$this->DB_TYPE');
        define('DB_HOST', '$this->DB_HOST');
        define('DB_NAME', '$this->DB_NAME');
        define('DB_USER', '$this->DB_USER');
        define('DB_PASS', '$this->DB_PASS');
        
        /**
        * Configuración : Vistas
        *
        * PATH_VIEWS es la ruta donde se encuentran los archivos con las vistas
        * PATH_VIEW_FILE_TYPE es la extensión de las vistas, en este caso usaremos motor plantilla twig.
        */
        define('PATH_VIEWS', 'app/views/');
        define('PATH_VIEW_FILE_TYPE', '.twig');";
        
        $myfile = fopen($fichero, "w");
        fwrite($myfile, $cadena, strlen($cadena));
        fclose($myfile);
    }
}