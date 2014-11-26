<?php

/**
 * clase Sesion
 *
 * Reliza las acciones de la session. crea una sesion cuando no haya alguna existente, establece y obtienes valores de la sesion
 *  y cierra la sesión cuando el usuario hace un logout.
 *  
 *  @author Carlos
 */
class Session {
	/**
	 * Comienza la sesión
	 */
	public static function start() {
		// si no existe, creamos una sesion
		if (session_id () == '') {
			session_start ();			
		}
	}
	
	/**
	 * Guarda una pareja key,value en la sesión
	 *
	 * @param mixed $key        	
	 * @param mixed $value        	
	 */
	public static function set($key, $value) {
		$_SESSION [$key] = $value;
	}
	
	/**
	 * Eliminar una key de la sesión
	 *
	 * @param unknown $key        	
	 */
	public function _unset($key) {
		if (isset ( $_SESSION )) {
			unset ( $_SESSION [$key] );			
		}
	}
	
	/**
	 * devuelve el valor de una key del array $_session
	 *
	 * @param mixed $key        	
	 * @return mixed
	 */
	public static function get($key) {
		if (isset ( $_SESSION [$key] )) {
			return $_SESSION [$key];
		}
	}
	
	/**
	 * borra la sesion (logout)
	 */
	public static function destroy() {
		session_destroy ();
	}
}