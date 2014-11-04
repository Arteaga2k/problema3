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
		)
		 );
	}
	
	/**
	 * ACCIÓN: añadir envío
	 * http://problema1/envios/add
	 * Recoge la información del formulario, método POST y redirecciona a envios/index
	 */
	public function set() {
		// cargamos el modelo y realizamos la acción
		$envios_model = $this->loadModel ( 'EnviosModel' );
		$provincias = $envios_model->getProvincias ();
		//var_dump($provincias);
		// Comprobamos que Si contiene informacin
		if (isset ( $_REQUEST ['setEnvio'] )) {
			
			
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
}