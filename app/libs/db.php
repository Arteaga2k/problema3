<?php

/**
 * @author Carlos
 * Implementación del patrón singleton
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
                die('Connection error: ' . $e->getMessage());
            }
        }
        return self::$db;
    }
}