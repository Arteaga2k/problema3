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
		// obtenemos listado provincias
		$provincias = $envios_model->getProvincias ();
		
		// validamos datos recibidos del formulario
		if ($this->valPost ( 'setEnvio', '' )) {
			
			if (! $this->filterValue ( $this->valPost ( 'nombre', '' ), 'texto' )) {
				$errores ['nombre'] = 'Introduce un nombre correcto';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'apellido1', '' ), 'texto' )) {
				$errores ['apellido1'] = 'Introduce un apellido correcto';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'apellido2', '' ), 'texto' )) {
				$errores ['apellido2'] = 'Introduce un apellido correcto';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'email', '' ), 'email' )) {
				$errores ['email'] = 'Introduce un email correcto';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'direccion', '' ), 'alfanum' )) {
				$errores ['direccion'] = 'Introduce una dirección correcta';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'telefono1', '' ), 'numerico' )) {
				$errores ['telefono1'] = 'Introduce un teléfono';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'telefono2', '' ), 'numerico' )) {
				$errores ['telefono2'] = 'Introduce una teléfono';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'cp', '' ), 'numerico' )) {
				$errores ['cp'] = 'Introduce un código postal correctoo';
			}
			
			if (! $this->filterValue ( $this->valPost ( 'poblacion', '' ), 'texto' )) {
				$errores ['poblacion'] = 'Introduce una poblacion';
			}
			var_dump($errores);
			if (! empty ( $errores )) {
				
				// var_dump ( $errores );
				
				// creamos la vista, enviamos como parámetro datos de envío obtenidos				
				$this->render ( 'envios/form_envio', array (
						'title' => 'Nuevo envío',
						'provincias' => $provincias,
						'errores' => $errores 
				) );
			}
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
	 *        	de el envío a eliminar
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
}