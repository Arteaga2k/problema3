<?php
class Envios extends Controller {
	
	/**
	 * PÁGINA: index
	 * http://problema1/envios/index
	 */
	public function index() {
		// load a model, perform an action, pass the returned data to a variable
		$envios_model = $this->loadModel ( 'EnviosModel' );
		$envios = $envios_model->getAllEnvios ();
		
		// creamos la vista, enviamos como parámetro datos de envío obtenidos
		$this->render ( 'envios/index', array (
				'envios' => $envios 
		) );
	}
	
	/**
	 * ACCIÓN: añadir envío
	 * http://problema1/envios/add
	 * Recoge la información del formulario, método POST y redirecciona a envios/index
	 */
	public function add() {
		// cargamos el modelo y realizamos la acción
		$envios_model = $this->loadModel ( 'EnviosModel' );
		$provincias = $envios_model->getProvincias ();
		
		// Comprobamos que Si contiene informacin
		if (isset ( $_REQUEST ['setEnvio'] )) {
			if (isset ( $_REQUEST ['nombre'] )) {
				if (!$this->filterValue($_REQUEST['nombre'], 'texto')){
					$errores['nombre'] = 'Introduce un nombre correcto';
				}
					
			}
			if (isset ( $_REQUEST ['apellido1'] )) {
				if (!$this->filterValue($_REQUEST['nombre'], 'texto')){
					$errores['apellido1'] = 'Introduce un apellido correcto';
				}
					
			}
			if (isset ( $_REQUEST ['apellido2'] )) {
				if (!$this->filterValue($_REQUEST['nombre'], 'texto')){
					$errores['apellido2'] = 'Introduce un apellido correcto';
				}
					
			}
			
			echo $this->filterName ( $_REQUEST ['nombre'] );
		} else {
			
			// cargamos la página índice con datos actualizados
			// header('location: ' . URL . 'envios/index');
			
			// creamos la vista, enviamos como parámetro datos de envío obtenidos
			$this->render ( 'envios/form_envio', array (
					'title' => 'Nuevo envío',
					'provincias' => $provincias 
			) );
		}
	}
	
	/**
	 * ACCIÓN: eliminar envío
	 * http://problema1/envios/delete
	 *
	 * @param int $envio_id
	 *        	de el env�o a eliminar
	 */
	public function delete($envio_id) {
		
		// si existe $envio_id
		if (isset ( $envio_id )) {
			// cargamos el modelo y realizamos la acción
			$envios_model = $this->loadModel ( 'EnviosModel' );
			$result = $envios_model->delete ( $envio_id );
			
			echo 'el resultado es' . $result;
		}
		
		// Mostramos el listado de envios refrescado
		header ( 'location: ' . URL . 'envios' );
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
		 * "/^[0-9]*$/"; // Solo cadena vacia o numeros
		 *
		 * "/^[0-9]+$/"; // Solo numeros. No se admite cadena vacia
		 *
		 * "/^[a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas. No se admite cadena vacia
		 *
		 * "/^[0-9a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas y numeros. No se admite cadena vacia
		 */
		if ($tipo = "texto") {
			$field = filter_var ( trim ( $field ), FILTER_SANITIZE_STRING );
			
			// Valida cadena de texto
			if (filter_var ( $field, FILTER_VALIDATE_REGEXP, array (
					"options" => array (
							"regexp" => "/^[a-zA-Z]+$/" 
					) 
			) ))
				return $field;
		} else if ($tipo = "numerico") {
			$field = filter_var ( trim ( $field ), FILTER_SANITIZE_NUMBER_INT );
			
			// Valida cadena de texto
			if (filter_var ( $field, FILTER_VALIDATE_REGEXP, array (
					"options" => array (
							"regexp" => "/^[0-9]+$/" 
					) 
			) ))
				return $field;
		} else { // email
			$field = filter_var ( trim ( $field ), FILTER_SANITIZE_EMAIL );
			
			// Valida email
			if (filter_var ( $field, FILTER_VALIDATE_EMAIL ))
				return $field;
		}
		
		return false;
	}
}