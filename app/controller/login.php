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
		
		// cargamos modelo, realizamos accion, guardamos resultado en una variable
		$zona_model = $this->loadModel ( 'ZonaModel' );
		$zonas = $zona_model->getZonas ();
        
        $this->render('login/form_login', array(
        	'login' => TRUE, 
            'zonas' => $zonas
        ))
		;
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
			$usuario_model = $this->loadModel ( 'UsuarioModel' );
			// filtramos y sanitizamos formulario
			$dataLogin = $this->filtraFormulario ( $this->formLogin () );
			
			// Si validación ok
			if ($this->validation ( $dataLogin )) {
				// Obtenemos datos del usuario que quiere hacer login
				$user = $usuario_model->getUsuarioByEmail ( $dataLogin ['datos'] ['email'] );
				$user ['zona'] = $_REQUEST ['zona'];
				
				if ($user) {
					// si coinciden passwords hasheadas, acceso ok
					if (password_verify ( $dataLogin ['datos'] ['password_hash'], $user ['password_hash'] )) {
						// guardamos datos usuario en sesion
						$this->guardaDatosSesion ( $user );
						
						// si la casilla recordar está marcada escribimos la cookie
						if (isset ( $_POST ['remember'] )) {
							
							// string formado por id usuario, random string y hash combinado de ambos
							$cookie_string = $usuario_model->rememberToken ( $user );
							
							// guardamos cookie
							setcookie ( 'rememberme', $cookie_string, time () + COOKIE_RUNTIME, "/", null );
						}
						
						header ( 'location: ' . URL . 'home/index' );
					}
				}
			}
		}
	}
	
	/**
	 * ACCIÓN: Login con cookies
	 */
	function loginConCookie() {
		$cookie = isset ( $_COOKIE ['rememberme'] ) ? $_COOKIE ['rememberme'] : '';
		
		$usuario_model = $this->loadModel ( 'UsuarioModel' );
		$zona_model = $this->loadModel ( 'ZonaModel' );
		// por defecto debemos asignar una zona, en este caso la primera de la tabla zonas
		$zona = $zona_model->getZonaDefault ();
		$user = $usuario_model->getUsuarioCookie ( $cookie );
		$user ['zona'] = $zona ['ZONA'];
		
		if (! empty ( $user )) {
			$this->guardaDatosSesion ( $user );
			//header ( 'location: ' . URL . 'home/index' );
		} else {
			
			// eliminamos cookie invalidad para evitar un bucle infinito
			$usuario_model->deleteCookie ();
			// redireccionamos al formulario login
			//header ( 'location: ' . URL . 'login/index' );
		}
	}
	
	/**
	 * Guarda en el array de la sesion los datos del usuario logueado
	 * y el estado de usuario logueado
	 */
	public function guardaDatosSesion($user) {
		// guardamos datos en la sesion
		Session::start ();
		Session::set ( 'usuario_logueado', true );
		Session::set ( 'usuario_id', $user ['id_usuario'] );
		Session::set ( 'usuario_nombre', $user ['username'] );
		Session::set ( 'usuario_email', $user ['email'] );
		Session::set ( 'usuario_zona', $user ['zona'] );
		
		$params = json_decode ( $user ['configuracion'], true );
		
		foreach ( $params as $key => $value ) {
			session::set ( $key, $value );
		}
		
		var_dump($_SESSION);
	}
	
	/**
	 * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
	 *
	 * @return multitype:string
	 */
	public function formLogin() {
		// campos esperados del formulario envio -nombre/tipo-
		$formLogin = array (
				'email' => 'email',
				'password_hash' => 'alfanum' 
		);
		
		return $formLogin;
	}
}