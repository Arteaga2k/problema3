<?php
class Zona extends Controller {
	
	/**
	 * PÁGINA: index
	 *
	 * http://problema1/zona/index
	 */
	public function index() {
		// cargamos modelo, realizamos accion, guardamos resultado en una variable
		$zona_model = $this->loadModel ( 'ZonaModel' );
		$zonas = $zona_model->getZonas ();
		$zona = $zona_model->getZona ( $_SESSION ['usuario_zona'] );
		
		// creamos la vista, pasamos datos de envío obtenidos
		$this->render ( 'zonas/index', array (
				'tabla' => 'Zonas',
				'cabecera' => 'Lista zonas administrativas',
				'usuario' => $_SESSION ['usuario_nombre'],
				'zonas' => $zonas,
				'zona_usuario' => $zona ['nombrezona'] 
		) );
	}
	
	/**
	 * PÁGINA: add
	 *
	 * http://problema1/zona/add
	 */
	public function add() { // cargamos modelo, realizamos accion, guardamos resultado en una variable
		$zona_model = $this->loadModel ( 'ZonaModel' );
		$zonas = $zona_model->getZonas ();
		$zona = $zona_model->getZona ( $_SESSION ['usuario_zona'] );
		
		// Mostramos datos del envío a editar
		$this->render ( 'zonas/form_zona', array (
				'tabla' => 'Zonas',
				'cabecera' => 'Añadir zona',
				'usuario' => $_SESSION ['usuario_nombre'],
				'accion' => 'add_accion',
				'zonas' => $zonas,
				'zona_usuario' => $zona ['nombrezona'] 
		) );
	}
	
	/**
	 * ACCIÓN: Añadir una nueva zona
	 *
	 * recoge campos del formulario add_zona
	 */
	public function add_accion() {
		if (isset ( $_REQUEST ['add_accion'] )) {
			// cargamos el modelo
			$zona_model = $this->loadModel ( 'ZonaModel' );
			
			// filtramos y sanitizamos formulario
			$data = $this->filtraFormulario ( $this->formZonas () );
			
			// Si validación ok
			if ($this->validation ( $data )) {
				// insertamos nuevo envío y redireccionamos a envios index
				$zona_model->addZona ( $data ['datos'] );
				header ( 'location: ' . URL . 'zona' );
			} else {
				$this->render ( 'zonas/form_zona', array (
						'tabla' => 'Zonas',
						'cabecera' => 'Añadir zona',
						'usuario' => $_SESSION ['usuario_nombre'],
						'accion' => 'add_accion',
						'datos' => $data ['datos'],
						'errores' => $data ['errores'] 
				) );
			}
		}
	}
	
	/**
	 * PÁGINA: editar zona
	 *
	 * http://problema1/zona/editar/id
	 *
	 * @param int $zona_id
	 *        	identificador tabla envío
	 */
	public function editar($zona_id = NULL) {
		// si existe $zona_id
		if (isset ( $zona_id )) {
			// cargamos el modelo
			$zona_model = $this->loadModel ( 'ZonaModel' );
			// obtenemos datos del envío a editar
			$data ['datos'] = $zona_model->getZona ( $zona_id );
			
			// Mostramos datos del envío a editar
			$this->render ( 'zonas/form_zona', array (
					'tabla' => 'Zonas',
					'cabecera' => 'Editar zona',
					'usuario' => $_SESSION ['usuario_nombre'],
					'accion' => 'editar_accion',
					'datos' => $data ['datos'],
					'id_zona' => $zona_id 
			) );
		}
	}
	
	/**
	 * ACCIÓN: editar un envío
	 *
	 * recoge campos del formulario edición envio
	 */
	public function editar_accion() {
		
		// Llamada desde formulario editar para guardar cambios
		if (isset ( $_REQUEST ['editar_accion'] )) {
			// cargamos el modelo y realizamos la acción
			$zona_model = $this->loadModel ( 'ZonaModel' );
			
			// filtramos y sanitizamos formulario
			$data = $this->filtraFormulario ( $this->formZonas () );
			
			// Si validación ok
			if ($this->validation ( $data )) {
				// insertamos nuevo envío y redireccionamos a envios index
				$zona_model->editZona ( $data ['datos'], $_REQUEST ['id_zona'] );
				header ( 'location: ' . URL . 'zona/index' );
			} else {
				$this->render ( 'zonas/form_zona', array (
						'tabla' => 'Zonas',
						'cabecera' => 'Editar zona',
						'usuario' => $_SESSION ['usuario_nombre'],
						'accion' => 'editar_accion',
						'id_zona' => $_REQUEST ['id_zona'],
						'datos' => $data ['datos'],
						'errores' => $data ['errores'] 
				) );
			}
		} else {
			// Redireccionamos al listado de envíos
			header ( 'location: ' . URL . 'zona/index' );
		}
	}
	
	/**
	 * PÁGINA: eliminar zona
	 *
	 * http://problema1/_templates/confirmacion/
	 *
	 * @param int $id_zona
	 *        	identificador tabla zona
	 */
	public function eliminar($id_zona) {
		// si existe $envio_id
		if (isset ( $id_zona )) {
			
			$this->render ( '_templates/confirmacion', array (
					'tabla' => 'Zonas',
					'cabecera' => 'Eliminar zona',
					'usuario' => $_SESSION ['usuario_nombre'],
					'accion' => 'eliminar_accion',
					'id' => $id_zona 
			) );
		}
	}
	
	/**
	 * ACCIÓN: eliminar zona
	 *
	 * http://problema1/zona/eliminar/id
	 *
	 * @param int $envio_id
	 *        	identificador tabla zona
	 */
	public function eliminar_accion($id_zona) {
		// cargamos el modelo
		$zona_model = $this->loadModel ( 'ZonaModel' );
		// realizamos la acción
		$confirmacion = $zona_model->deleteZona ( $id_zona );
		
		if ($confirmacion) {
			// Mostramos el listado de envios refrescado
			header ( 'location: ' . URL . 'zona' );
		} else {
			$this->render ( '_templates/error', array (
					'tabla' => 'Zona',
					'cabecera' => 'Eliminar zona',
					'usuario' => $_SESSION ['usuario_nombre'],
					'mensaje' => 'No se puede elimnar este registro' 
			) );
		}
	}
	
	/**
	 * ACCION: cambiar zona de recepcion
	 */
	public function cambiaZona($id_zona) {
		// Guardamos el cambio de zona en la sesion
		Session::set ( 'usuario_zona', $id_zona );
		// redireccionamos a la pagina principal
		header ( 'location: ' . URL . 'home/index' );
	}
	
	/**
	 * ACCIÓN: consultar envío
	 *
	 * http://problema3/usuario/consulta
	 */
	public function consulta() {
		if (isset ( $_REQUEST ['consulta'] )) {
			
			// filtramos y sanitizamos formulario
			$data = $this->filtraFormulario ( array (
					'ver' => 'numerico' 
			) );
			
			if ($this->validation ( $data )) {
				$id_zona = $data ['datos'] ['ver'];
				// cargamos el modelo
				$zona_model = $this->loadModel ( 'ZonaModel' );
				// obtenemos datos del envío a editar
				$data ['datos'] = $zona_model->getZona ( $id_zona );
				
				if (! empty ( $data ['datos'] )) {
					$this->render ( 'zonas/form_zona', array (
							'usuario' => $_SESSION ['usuario_nombre'],
							'cabecera' => 'Consultar zona',
							'accion' => 'consulta',
							'datos' => $data ['datos'] 
					) );
				} else {
					header ( 'location: ' . URL . 'zona' );
				}
			} else {
				header ( 'location: ' . URL . 'zona' );
			}
		}
	}
	
	/**
	 * Rellena el array con campos esperados del formulario envío -nombre/tipo-
	 *
	 * @return multitype:string
	 */
	public function formZonas() {
		// campos esperados del formulario envio -nombre/tipo-
		$formZona = array (
				'nombrezona' => 'alfanum' 
		);
		
		return $formZona;
	}
}