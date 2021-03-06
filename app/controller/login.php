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
            'login' => TRUE,
            'zonas' => $zonas,
            'tema' => 'blue-theme.css'
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
            $usuario_model = $this->loadModel('UsuarioModel');
            // filtramos y sanitizamos formulario
            $dataLogin = $this->filtraFormulario($this->formLogin());
            
            // Si validación ok
            if ($this->validation($dataLogin)) {
                // Obtenemos datos del usuario que quiere hacer login
                $user = $usuario_model->getUsuarioByEmail($dataLogin['datos']['email']);
                $user['zona'] = $_REQUEST['zona'];
                // si nos ha devuelto un usuario
                if (isset($user['id_usuario'])) {
                    // si coinciden passwords hasheadas, acceso ok
                    if (password_verify($dataLogin['datos']['password_hash'], $user['password_hash'])) {
                        // guardamos datos usuario en sesion
                        $this->guardaDatosSesion($user);
                        
                        // si la casilla recordar está marcada escribimos la cookie
                        if (isset($_POST['remember'])) {
                            
                            // string formado por id usuario, random string y hash combinado de ambos
                            $cookie_string = $usuario_model->rememberToken($user);
                            
                            // guardamos cookie
                            setcookie('rememberme', $cookie_string, time() + session::get('COOKIE_RUNTIME'), "/", FALSE);                           
                        }
                        
                        header('location: ' . URL . 'home/index');
                    }
                }
            }
        }
    }
    
    /**
     * PÁGINA: 
     */
    function registroUsuario(){       
        
        $this->render('usuarios/form_registro', array(
            'tabla' => 'Login',
            'login' => true,
            'cabecera' => 'Registro usuario',          
            'accion' => 'registro_accion',           
            'tema' => 'blue-theme.css'
          
        ));
    }
    
    /**
     * ACCION
     */
    function registro_accion(){
        if (isset($_REQUEST['registro_accion'])) {
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');          
        
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formRegUsuario());
        
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $usuario_model->addUsuario($data['datos']);
                header('location: ' . URL . 'login');
            } else {
                $this->render('usuarios/form_registro', array(
                    'tabla' => 'Login',
                    'login' => 'true',
                    'title' => 'Alta usuario',
                    'cabecera' => 'Registro usuario',
                    'accion' => 'registro_accion',
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],                 
                    'tema' => 'blue-theme.css'
                ));
            }
        }
    }

    /**
     * ACCIÓN: Login con cookies
     */
    function loginConCookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        
        $usuario_model = $this->loadModel('UsuarioModel');
        $zona_model = $this->loadModel('ZonaModel');
        // por defecto debemos asignar una zona, en este caso la primera de la tabla zonas
        $zona = $zona_model->getZonaDefault();
        $user = $usuario_model->getUsuarioCookie($cookie);
        $user['zona'] = $zona['ZONA'];
        
        if (! empty($user)) {
            $this->guardaDatosSesion($user);          
            header('location: ' . URL . 'home/index');
        } else {            
            // eliminamos cookie invalidad para evitar un bucle infinito
            $usuario_model->deleteCookie();
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
        Session::set('usuario_hora_inicio', date('H:i'));
        
        $params = json_decode($user['configuracion'], true);
        
        //var_dump($params);
        
        foreach ($params as $key => $value) {
            session::set($key, $value);
        }       
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
    
    /**
     * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formRegUsuario()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formEnvio = array(
            'username' => 'alfanum',
            'email' => 'email',
            'password_hash' => 'alfanum'
        );
    
        return $formEnvio;
    }
}