<?php

/**
 * CLASE login
 * 
 * Controlador para el logueado de un usuario
 * 
 * @author Carlos 
 * @version 1.0
 */
class Login extends Controller {
	
	/**
	 * PÁGINA: index
	 *
	 * Nos redirecciona automáticamente al formulario de login
	 *
	 * http://problema1/login/index
	 */
	public function index() {
		//cargamos modelo, realizamos accion, guardamos resultado en una variable
		$zona_model = $this->loadModel ( 'ZonaModel' );
		//$zonas = $zona_model->getAllZonas ();
		
		$this->render ( 'login/form_login', array (
				'zonas' => 'administracion'//$zonas 
		) );
	}
	
	/**
	 * ACCIÓN: login
	 *
	 * http://problema1/login/login
	 *
	 * Recoge la información del formulario, método POST y redirecciona a envios/index
	 */
	function login() {
		if (isset ( $_REQUEST ['login'] )) {		
			
			
			// cargamos el modelo y realizamos la acción
			$login_model = $this->loadModel ( 'LoginModel' );
			// filtramos y sanitizamos formulario
			$dataLogin = $this->filtraFormulario ( $this->formLogin () );
			
			// Si validación ok
			if ($this->validation ( $dataLogin )) {
				// Obtenemos datos del usuario que quiere hacer login
				$user = $login_model->loginUsuario ( $dataLogin ['datos']['email'] );
				var_dump($_POST);
				
				if ($user) {					
					// si coinciden passwords hasheadas, acceso ok
					if (password_verify ( $dataLogin ['datos']['password_hash'], $user ['password_hash'] )) {
						
						// guardamos datos en la sesion
						Session::start ();
						Session::set ( 'usuario_logueado', true );
						Session::set ( 'usuario_id', $user ['id_usuario'] );
						Session::set ( 'usuario_nombre', $user ['username'] );
						Session::set ( 'usuario_email', $user ['email'] );
						
						// si la casilla recordar está marcada escribimos la cookie
						if (isset ( $_POST ['remember'] )) {
						   
							// string formado por id usuario, random string y hash combinado de ambos
							$cookie_string = $login_model->rememberToken ();
							// guardamos cookie
							echo setcookie ( 'rememberme', $cookie_string, time () + COOKIE_RUNTIME, "/", COOKIE_DOMAIN );
						}
						
						//header ( 'location: ' . URL . 'home/index' );
					}
				}
			}
		}
	}
	
	/**
	 * ACCIÓN: Login con cookies
	 */
	function loginConCookie() {
		$login_model = $this->loadModel ( 'Login' );
		$login_successful = $login_model->loginWithCookie ();
		
		if ($login_successful) {
			header ( 'location: ' . URL . 'home/index' );
		} else {
			// delete the invalid cookie to prevent infinite login loops
			$login_model->deleteCookie ();
			// if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
			header ( 'location: ' . URL . 'login/index' );
		}
	}
	
	/**
	 * Log out ddel usuario, borramos cookie y sesion
	 */
	public function logout() {
		// eliminamos cookie según explica el enlace de abajo
		// ponemos una fecha antigua.
		// ver http://stackoverflow.com/a/686166/1114320
		setcookie ( 'rememberme', false, time () - (3600 * 3650), '/', COOKIE_DOMAIN );
		
		// delete the session
		Session::destroy ();
	}
	
	/**
	 * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
	 *
	 * @return multitype:string
	 */
	public function formLogin() {
		// campos esperados del formulario envio -nombre/tipo-
		$formEnvio = array (
				'email' => 'email',
				'password_hash' => 'alfanum' 
		);
		
		return $formEnvio;
	}
}