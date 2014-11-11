<?php

/**
 * @author Carlos
 * Este ser� el controlador base, que heredar�n el resto de controladores 
 * Desde aqu� cargaremos el modelo requerido y renderizamos la vista 
 */
class Controller {
	
	/**
	 *
	 * @var unknown
	 */
	protected $_errores;
	
	/**
	 * Carga el modelo requerido.
	 * Note that the model class name is written in "CamelCase", the model's filename is the same in lowercase letters
	 *
	 * @param string $model_name
	 *        	El nombre del modelo a cargar
	 * @return object modelo
	 */
	public function loadModel($model_name) {
		// construyo cadena y cargo el modelo
		require 'app/models/' . strtolower ( $model_name ) . '.php';
		// return el modelo cargado
		return new $model_name ();
	}
	
	/**
	 * Función que carga la plantilla twig y genera la vista
	 *
	 * @param string $view        	
	 * @param array $data_array        	
	 */
	public function render($view, $data_array = array()) {
		// TODO cargar twig autoloader
		
		// Cargamos el motor de plantillas Twig
		$twig_loader = new Twig_Loader_Filesystem ( PATH_VIEWS );
		$twig = new Twig_Environment ( $twig_loader );
		
		// Genera la vista
		echo $twig->render ( $view . PATH_VIEW_FILE_TYPE, $data_array );
	}
}