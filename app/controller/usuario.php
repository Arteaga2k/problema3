<?php
/**
 * 
 * @author Carlos
 *
 */
class Usuario extends Controller {
	public function index() {
	}
	
	/**
	 * PÁGINA: alta usuario
	 */
	public function add() {
		$this->render ( 'usuarios/form_registro', array (
				'tabla' => 'Usuario',
				'title' => 'Registro usuario' 
		) );
	}
	
	/**
	 * ACCIÓN: recoge información formulario de registro
	 */
	public function add_accion() {		
	
		if (isset($_REQUEST['add_accion'])) {
			// cargamos el modelo y realizamos la acción
			$usuario_model = $this->loadModel('UsuarioModel');			
		
			// filtramos y sanitizamos formulario
			$data = $this->filtraFormulario($this->formAddUsuario());
		
			// Si validación ok
			if ($this->validation($data)) {
				// insertamos nuevo envío y redireccionamos a envios index
				$usuario_model->addUsuario($data['datos']);
				header('location: ' . URL . 'home');
			} else {
				$this->render('usuarios/form_registro', array(
						'tabla' => 'Usuario',
						'title' => 'Registro usuario',
						'datos' => $data['datos'],
						'errores' => $data['errores']
				));
			}
		}
		
	}
	public function editar() {
	}
	public function eliminar() {
	}
	
	/**
	 * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
	 *
	 * @return multitype:string
	 */
	public function formAddUsuario() {
		// campos esperados del formulario envio -nombre/tipo-
		$formEnvio = array (
				'username' => 'alfanum',
				'email' => 'email',
				'password_hash' => 'alfanum' 
		);
		
		return $formEnvio;
	}
}