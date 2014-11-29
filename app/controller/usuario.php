<?php
/**
 * 
 * @author Carlos
 *
 */
class Usuario extends Controller {
/**
     * PÁGINA: index
     *
     * http://problema1/usuario/index
     */
    public function index(){
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $usuario_model = $this->loadModel('UsuarioModel');
        $zona_model = $this->loadModel('ZonaModel');
        
        $usuarios = $usuario_model->getUsuarios();        
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('usuarios/index', array(
            'tabla' => 'Usuarios', 
            'cabecera' => 'Lista usuarios',
            'usuario' => $_SESSION['usuario_nombre'],
            'usuarios' => $usuarios,   
            'zona_usuario' => $zona['nombrezona'],
        ));
        
    }
	
	/**
	 * PÁGINA: alta usuario
	 */
	public function add() {
		$this->render ( 'usuarios/form_registro', array (
				'tabla' => 'Usuario',
				'title' => 'Alta usuario' 
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
						'title' => 'Alta usuario',
						'datos' => $data['datos'],
						'errores' => $data['errores']
				));
			}
		}
		
	}
	
	
	/**
	 * Log out ddel usuario, borramos cookie y sesion
	 */
	public function logout()
	{
	    // eliminamos cookie según explica el enlace de abajo
	    // ponemos una fecha antigua.
	    // @see http://stackoverflow.com/a/686166/1114320
	    setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
	
	    // borramos sesion
	    Session::destroy();
	    
	    // redireccionamos al formulario de login
	     header('location: ' . URL . 'login');
	    
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