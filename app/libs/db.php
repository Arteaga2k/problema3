<?php

/** 
 * Clase Db
 * 
 * Implementación del patrón singleton, devuelve una instancia de la conexión a la bbdd
 * 
 * @author Carlos * 
 **/
class Db
{

    /**
     * Contenedor Instancia de la Clase
     *
     * @var unknown
     */
    private static $db;
    
   
    
    
    /**
     * El método singleton
     *
     * @return PDO
     */
    public static function singleton()
    {
        if (! self::$db) {
            try {               
                $dsn = DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
                self::$db = new PDO($dsn, DB_USER, DB_PASS);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
             
            } catch (PDOException $e) {   
               
                //die('Connection error: ' . $e->getMessage());
                if (! file_exists('app/config.php')) {
                  header('location: ' . URL . 'instalador/error');
                }else{
                    die('Connection error: ' . $e->getMessage());
                }
            }
        }
        return self::$db;
    }
}