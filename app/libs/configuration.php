<?php

/**
 * Clase Configuration
 * 
 * comprueba si usuario está logeado. En la aplicación los diferentes controladores la usarán Autorizacion::handleLogin() para
 * comprobar si el usuario está logueado
 * 
 * @author Carlos
 */
class Configuration
{

    /**
     * Guarda una serie de valores por defecto para todo usuario
     */
    public static function porDefecto()
    {
        $parametros = array();
        
        $parametros['COOKIE_RUNTIME'] = 1209600; // dos semanas
        $parametros['REGS_PAG'] = 10; // diez registros por página
                                      
        // guardamos cambios en la sesion
        foreach ($data as $key => $value) {
            session::set($key, $value);
        }
    }
}