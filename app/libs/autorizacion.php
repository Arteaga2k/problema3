<?php

/**
 * Clase Autorizacion
 * 
 * comprueba si usuario está logeado. En la aplicación los diferentes controladores la usarán Autorizacion::handleLogin() para
 * comprobar si el usuario está logueado
 * 
 * @author Carlos
 */
class Autorizacion {
	public static function checkLogin() {
		// Inicialiazamos la sesion
		Session::start ();
		// si usuario no está logueado, destruimos sesión y redireccionamos al formulario de login
		if (! isset ( $_SESSION ['usuario_logueado'] )) {
			
			if (isset ( $_COOKIE ['rememberme'] )) {
				header ( 'location: ' . URL . 'login/loginConCookie' );
			} else {
				// destruimos session
				Session::destroy ();
				// redireccionamos al formulario login
				header ( 'location: ' . URL . 'login' );
			}
		}
	}
}