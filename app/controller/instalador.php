<?php

/**
 * CLASE home
 *
 * Controlador página principal (dashboard)
 *
 * @author Carlos
 */
class Instalador extends Controller {
	public function __construct() {
		define ( 'URL', 'http://localhost/problema3/' );
		define ( 'PATH_VIEWS', 'app/views/' );
		define ( 'PATH_VIEW_FILE_TYPE', '.twig' );
	}
	
	/**
	 * PÁGINA: Mensaje bienvenida instalacion
	 *
	 * http://problema3/
	 */
	public function index() {
		$this->render ( 'instalador/step0', array (
				'login' => TRUE 
		) );
	}
	
	/**
	 * PÁGINA: dashboard
	 *
	 * http://problema3/index
	 */
	public function configuracion() {
		$this->render ( 'instalador/step1', array (
				'login' => TRUE 
		) );
	}
}