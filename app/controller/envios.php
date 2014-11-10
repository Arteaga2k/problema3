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
				'title' => 'Lista envíos',
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
		// obtenemos listado provincias a mostrar
		$provincias = $this->getProvincias ( $envios_model );
		
		// comprobamos si existe el formulario
		if (isset ( $_REQUEST ['add'] )) {
			require 'app/core/form_filter.php';
			$helperForm = new FilterForm ();
			
			$formEnvio = $this->setCamposForEnvio ();
			$data = $helperForm->checkForm ( $formEnvio );
			
			// guardamos datos y errores posibles del formulario en arrays que pasaremos a la plantilla
			$dataForm = $data ['datos'];
			$errores = $data ['errores'];
			
			var_dump ( $dataForm );
			
			// Si existen errores recargamos el formulario con los errores existentes
			if (! empty ( $errores )) {
				$this->render ( 'envios/form_envio', array (
						'title' => 'Añadir envío',
						'provincias' => $provincias,
						'datos' => $dataForm,
						'errores' => $errores 
				) );
			} else { // no hay errores
			         // insertamos nuevo envío y redireccionamos a envios index
				$envios_model->add ();
				header ( 'location: ' . URL . 'envios' );
			}
		} else {
			
			// cargamos la página índice con datos actualizados
			// header('location: ' . URL . 'envios/index');
			
			// creamos la vista, enviamos como parámetro datos de envío obtenidos
			$this->render ( 'envios/form_envio', array (
					'title' => 'Nuevo envío',
					'accion' => 'add',
					'provincias' => $provincias 
			) );
		}
	}
	
	/**
	 * ACCIÓN: eliminar envío
	 * http://problema1/envios/delete/id
	 *
	 * @param int $envio_id
	 *        	identificador tabla envío
	 */
	public function delete($envio_id) {
		// si existe $envio_id
		if (isset ( $envio_id )) {
			// cargamos el modelo
			$envios_model = $this->loadModel ( 'EnviosModel' );
			// realizamos la acción
			$result = $envios_model->delete ( $envio_id );
			
			echo 'el resultado es' . $result;
		}
		
		// Mostramos el listado de envios refrescado
		header ( 'location: ' . URL . 'envios' );
	}
	
	/**
	 * ACCIÓN: editar envío
	 * http://problema1/envios/edit/id
	 *
	 * @param int $envio_id
	 *        	identificador tabla envío
	 */
	public function edit($envio_id) {
		// si existe $envio_id
		if (isset ( $envio_id )) {
			
			// comprobamos si existe el formulario
			if (isset ( $_REQUEST ['edit'] )) {
				require 'app/core/form_filter.php';
				$helperForm = new FilterForm ();
				
				$formEnvio = $this->setCamposForEnvio ();
				$data = $helperForm->checkForm ( $formEnvio );
				
				// guardamos datos y errores posibles del formulario en arrays que pasaremos a la plantilla
				$dataForm = $data ['datos'];
				$errores = $data ['errores'];
				
				var_dump ( $dataForm );
				
				// Si existen errores recargamos el formulario con los errores existentes
				if (! empty ( $errores )) {
					$this->render ( 'envios/form_envio', array (
							'title' => 'Editar envío',
							'provincias' => $provincias,
							'datos' => $dataForm,
							'errores' => $errores 
					) );
				} else { // no hay errores
				         // insertamos nuevo envío y redireccionamos a envios index
					$envios_model->edit ( $envio_id );
					header ( 'location: ' . URL . 'envios' );
				}
			} else {
				
				// cargamos la página índice con datos actualizados
				// header('location: ' . URL . 'envios/index');
				
				// creamos la vista, enviamos como parámetro datos de envío obtenidos
				$this->render ( 'envios/form_envio', array (
						'title' => 'Editar envío',
						'accion' => 'edit',
						'provincias' => $provincias 
				) );
			}
		}
	}
	
	/**
	 * Función que devuelve el listado de provincias
	 * 
	 * @param
	 *        	el objeto modelo $envios_model
	 */
	public function getProvincias($envios_model) {
		return $envios_model->getAllProvincias ();
	}
	
	/**
	 * Rellena el array con campos esperados del formulario envío -nombre/tipo-
	 * 
	 * @return multitype:string
	 */
	public function setCamposForEnvio() {
		// campos esperados del formulario -nombre/tipo-
		$formEnvio = array (
				'nombre' => 'texto',
				'apellido1' => 'texto',
				'apellido2' => 'texto',
				'email' => 'email',
				'direccion' => 'alfanum',
				'telefono1' => 'numerico',
				'telefono2' => 'numerico',
				'codpostal' => 'numerico',
				'poblacion' => 'texto',
				'fec_entrega' => 'date' 
		);
		
		return $formEnvio;
	}
}