<?php

/**
 * @author Carlos
 * Este ser� el controlador base, que heredar�n el resto de controladores 
 * Desde aqu� cargaremos el modelo requerido y renderizamos la vista 
 */
class Controller
{

    /**
     * Carga el modelo requerido.
     * Note that the model class name is written in "CamelCase", the model's filename is the same in lowercase letters
     * 
     * @param string $model_name
     *            El nombre del modelo a cargar
     * @return object modelo
     */
    public function loadModel($model_name)
    {
        // construyo cadena y cargo el modelo
        require 'app/models/' . strtolower($model_name) . '.php';
        // return el modelo cargado
        return new $model_name(); 
    }

    public function render($view, $data_array = array())
    {
        // TODO cargar twig autoloader
        
        // Cargamos el motor de plantillas Twig
        $twig_loader = new Twig_Loader_Filesystem(PATH_VIEWS);
        $twig = new Twig_Environment($twig_loader);
        
        // Genera la vista
        echo $twig->render($view . PATH_VIEW_FILE_TYPE, $data_array);
    }
    
    /**
     * Filtra el valor de una cadena recibida
     *
     * @param String $cadena
     * @param String $tipo
     * @return mixed|boolean
     */
    public function filterValue($cadena, $tipo) {
    
    	/*
    	 * Expresiones regulares
    	 *
    	 * "/^[0-9]*$/"; // Solo cadena vacia o numeros
    	 *
    	 * "/^[0-9]+$/"; // Solo numeros. No se admite cadena vacia
    	 *
    	 * "/^[a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas. No se admite cadena vacia
    	 *
    	 * "/^[0-9a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas y numeros. No se admite cadena vacia
    	 *
    	 * "#^[a-z0-9\s]+$#i"; // Alfanuméricos y espacio. No se admite cadena vacia
    	 */
    	if ($tipo == "texto") {
    		$field = filter_var ( trim ( $cadena ), FILTER_SANITIZE_STRING );
    			
    		// Valida cadena de texto
    		if (filter_var ( $field, FILTER_VALIDATE_REGEXP, array (
    				"options" => array (
    						"regexp" => "/^[a-zA-Z]+$/"
    				)
    		) ))
    			return $field;
    	} elseif ($tipo == "numerico") {
    		$field = filter_var ( trim ( $cadena ), FILTER_SANITIZE_NUMBER_INT );
    			
    		// Valida cadena de texto
    		if (filter_var ( $field, FILTER_VALIDATE_REGEXP, array (
    				"options" => array (
    						"regexp" => "/^[0-9]+$/"
    				)
    		) ))
    			return $field;
    	} elseif ($tipo == "alfanum") {
    		$field = filter_var ( trim ( $cadena ), FILTER_SANITIZE_STRING );
    			
    		// Valida cadena de texto
    		if (filter_var ( $field, FILTER_VALIDATE_REGEXP, array (
    				"options" => array (
    						"regexp" => "#^[a-z0-9\s]+$#i"
    				)
    		) ))
    			return $field;
    	} else { // email
    		$field = filter_var ( trim ( $cadena ), FILTER_SANITIZE_EMAIL );
    			
    		// Valida email
    		if (filter_var ( $field, FILTER_VALIDATE_EMAIL ))
    			return $field;
    	}
    
    	return false;
    }
    
    /**
     *
     * @param unknown $campo
     * @param string $default
     * @return unknown|string
     */
    public function valPost($campo, $default = '') {
    	if (isset ( $_REQUEST [$campo] ))
    		return $_REQUEST [$campo];
    	else
    		return $default;
    }
}