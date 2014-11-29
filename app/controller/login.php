<?php

/**
 * CLASE login
 * 
 * Controlador para el logueado de un usuario
 * 
 * @author Carlos 
 * @version 1.0
 */
class Login extends Controller
{

    /**
     * PÁGINA: index
     *
     * Nos redirecciona automáticamente al formulario de login
     *
     * http://problema1/login/index
     */
    public function index()
    {
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $zona_model = $this->loadModel('ZonaModel');
        $zonas = $zona_model->getZonas();
        
        $this->render('login/form_login', array(
            'zonas' => $zonas
        ));
    }

    /**
     * ACCIÓN: login
     *
     * http://problema1/login/login
     *
     * Recoge la información del formulario, método POST y redirecciona a envios/index
     */
    function login()
    {        
        if (isset($_REQUEST['login'])) {
            
            // cargamos el modelo y realizamos la acción
            $login_model = $this->loadModel('LoginModel');
            // filtramos y sanitizamos formulario
            $dataLogin = $this->filtraFormulario($this->formLogin());
            
            // Si validación ok
            if ($this->validation($dataLogin)) {
                // Obtenemos datos del usuario que quiere hacer login
                $user = $login_model->getUsuario($dataLogin['datos']['email']);
                $user['zona'] = $_POST['zona'];
               
                if ($user) {
                    // si coinciden passwords hasheadas, acceso ok
                    if (password_verify($dataLogin['datos']['password_hash'], $user['password_hash'])) {
                        // guardamos datos usuario en sesion
                        $this->guardaDatosSesion($user);
                        
                        // si la casilla recordar está marcada escribimos la cookie
                        if (isset($_POST['remember'])) {
                            
                            // string formado por id usuario, random string y hash combinado de ambos
                            $cookie_string = $login_model->rememberToken($user);
                            
                            // guardamos cookie
                            setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME,"/", null);
                        }                        
                        
                       header('location: ' . URL . 'home/index');
                    }
                }
            }
        }
    }

    /**
     * ACCIÓN: Login con cookies
     */
    function loginConCookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
              
        $login_model = $this->loadModel('LoginModel');
        $user = $login_model->getUsuarioCookie($cookie);
        
                
        if (! empty($user)) {
            $this->guardaDatosSesion($user);         
             header('location: ' . URL . 'home/index');
        } else {
           
            // eliminamos cookie invalidad para evitar un bucle infinito
            $login_model->deleteCookie();
            // redireccionamos al formulario login
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Guarda en el array de la sesion los datos del usuario logueado
     * y el estado de usuario logueado
     */
    public function guardaDatosSesion($user)
    {
        // guardamos datos en la sesion
        Session::start();
        Session::set('usuario_logueado', true);
        Session::set('usuario_id', $user['id_usuario']);
        Session::set('usuario_nombre', $user['username']);
        Session::set('usuario_email', $user['email']);
        Session::set('usuario_zona', $user['zona']);
    }

    /**
     * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formLogin()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formLogin = array(
            'email' => 'email',
            'password_hash' => 'alfanum'
        );
        
        return $formLogin;
    }
}