<?php

/**
 * 
 * @author Carlos
 *
 */
class Usuario extends Controller
{

    /**
     * PÁGINA: index
     *
     * http://problema1/usuario/index
     */
    public function index($pag = 1)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $usuario_model = $this->loadModel('UsuarioModel');
        $zona_model = $this->loadModel('ZonaModel');
        
        // comprueba si existe en sesion variables campos para filtrar busqueda
        $filtro = $this->compruebaFiltro();
        $totalRows = $usuario_model->getTotalRows($filtro);
        $pagination = $this->pagination($pag, $totalRows);
        
        $usuarios = $usuario_model->getUsuarios(isset($filtro) ? $filtro : null, $pagination['offset'], null);
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        $zonas = $zona_model->getZonas();
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('usuarios/index', array(
            'tabla' => 'Usuario',
            'cabecera' => 'Lista usuarios',
            'usuario' => $_SESSION['usuario_nombre'],
            'usuarios' => $usuarios,
            'hora' => Session::get('usuario_hora_inicio'),
            'zona_usuario' => $zona['nombrezona'],
            'zonas' => $zonas,
            'page' => $pagination['pag'],
            'totalpag' => $pagination['totalPag'],
            'inicio' => $pagination['inicio'],
            'fin' => $pagination['fin'],
            'filtro' => isset($filtro) ? $filtro : ''
        ));
    }

    /**
     * ACCIÓN: Filtro listado envío
     *
     * recoge campos del formulario filtrar listado
     */
    public function filtroPaginacion()
    {
        if (isset($_REQUEST['filtro'])) {
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formFiltro());
            
            // guardamos valores de campos a filtrar en la sesion
            Session::start();
            
            foreach ($data['datos'] as $key => $value) {
                if ($value) {
                    Session::set('filtro_' . $key, $value);
                }
            }
        }
        
        header('location: ' . URL . 'usuario');
    }

    /**
     * Comprueba si hay filtros de búsqueda guardados en sesion
     *
     * @return unknown
     */
    public function compruebaFiltro()
    {
        $filtro = '';
        if (isset($_SESSION['filtro_texto'])) {
            $filtro['filtro_texto'] = $_SESSION['filtro_texto'];
        }
        
        return $filtro;
    }

    /**
     * PÁGINA: alta usuario
     */
    public function add()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        $zona_model = $this->loadModel('Zonamodel');
        $zonas = $zona_model->getZonas();
        $zona = $zona_model->getZona(session::get('usuario_zona'));
        
        $this->render('usuarios/form_registro', array(
            'tabla' => 'Usuario',
            'cabecera' => 'Añadir usuario',          
            'accion' => 'add_accion',
            'zona_usuario' => $zona['nombrezona'],
            'usuario' => session::get('usuario_nombre'),
            'hora' => session::get('usuario_hora_inicio'),
            'zonas' => $zonas
        ));
    }

    /**
     * ACCIÓN: recoge información formulario de registro
     */
    public function add_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['add_accion'])) {
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');
            $zona_model = $this->loadModel('Zonamodel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            
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
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],
                    'hora' => session::get('usuario_hora_inicio'),
                    'hora' => session::get('usuario_hora_inicio'),
                    'zonas' => $zonas
                ));
            }
        }
    }

    /**
     * PÁGINA: editar usuario
     *
     * http://problema1/usuario/editar/id
     *
     * @param int $zona_id
     *            identificador tabla envío
     */
    public function editar($id_usuario = NULL)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $id_usuario
        if (isset($id_usuario)) {
            // cargamos el modelo
            $usuario_model = $this->loadModel('UsuarioModel');
            $zona_model = $this->loadModel('Zonamodel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            
            // obtenemos datos del envío a editar
            $data['datos'] = $usuario_model->getUsuarioById($id_usuario);
            
            // Mostramos datos del envío a editar
            $this->render('usuarios/form_registro', array(
                'tabla' => 'Usuario',
                'cabecera' => 'Editar usuario',
                'usuario' => session::get('usuario_nombre'),
                'hora' => session::get('usuario_hora_inicio'),
                'accion' => 'editar_accion',
                'id_usuario' => $id_usuario,
                'datos' => $data['datos'],
                'zona_usuario' => $zona['nombrezona'],
                'zonas' => $zonas
            ));
        }
    }

    /**
     * ACCIÓN: editar un usuario
     *
     * recoge campos del formulario edición usuario
     */
    public function editar_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // Llamada desde formulario editar para guardar cambios
        if (isset($_REQUEST['editar_accion'])) {
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formEditUsuario());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $usuario_model->editUsuario($data['datos'], $_REQUEST['id_usuario']);
                header('location: ' . URL . 'usuario/index');
            } else {
                $this->render('usuarios/form_registro', array(
                    'tabla' => 'Usuario',
                    'cabecera' => 'Editar usuario',
                    'usuario' => session::get('usuario_nombre'),
                    'accion' => 'editar_accion',
                    'id_usuario' => session::get('id_usuario'),
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
                ));
            }
        } else {
            // Redireccionamos al listado de envíos
            header('location: ' . URL . 'usuario/index');
        }
    }

    /**
     * PÁGINA: configuración
     *
     * http://problema1/usuario/configuracion
     */
    public function configuracion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        $zona_model = $this->loadModel('ZonaModel');
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('usuarios/configuracion', array(
            'tabla' => 'Configuración',
            'cabecera' => 'parámetros',
            'usuario' => session::get('usuario_nombre'),
            'zona_usuario' => $zona['nombrezona']
        ));
    }

    /**
     * ACCIÓN: recoge información formulario de configuración
     */
    public function configuracion_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['config_accion'])) {
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formConfiguracion());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $usuario_model->setConfigParams($data['datos'], Session::get('usuario_id'));
                
                header('location: ' . URL . 'home');
            } else {
                $this->render('usuarios/configuracion', array(
                    'tabla' => 'Configuración',
                    'cabecera' => 'parámetros',
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
                ));
            }
        }
    }

    /**
     * ACCIÓN: consultar envío
     *
     * http://problema3/usuario/consulta
     */
    public function consulta()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['consulta'])) {
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario(array(
                'ver' => 'numerico'
            ));
            
            if ($this->validation($data)) {
                $id_usuario = $data['datos']['ver'];
                // cargamos el modelo
                $usuario_model = $this->loadModel('UsuarioModel');
                $zona_model = $this->loadModel('Zonamodel');
                $zonas = $zona_model->getZonas();
                $zona = $zona_model->getZona(session::get('usuario_zona'));
                // obtenemos datos del envío a editar
                $data['datos'] = $usuario_model->getUsuarioById($id_usuario);
                
                if (! empty($data['datos'])) {
                    $this->render('usuarios/form_registro', array(
                        'usuario' => session::get('usuario_nombre'),
                        'cabecera' => 'Consultar usuario',
                        'accion' => 'consulta',
                        'datos' => $data['datos'],
                        'zona_usuario' => $zona['nombrezona'],
                        'hora' => session::get('usuario_hora_inicio'),
                        'zonas' => $zonas
                    ));
                } else {
                    header('location: ' . URL . 'usuario');
                }
            } else {
                header('location: ' . URL . 'usuario');
            }
        }
    }

    /**
     * PÁGINA: eliminar usuario
     *
     * http://problema3/usuario/eliminar/
     *
     * @param int $id_usuario
     *            identificador tabla usuario
     */
    public function eliminar($id_usuario)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $envio_id
        if (isset($id_usuario)) {
            $zona_model = $this->loadModel('Zonamodel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            
            $this->render('_templates/confirmacion', array(
                'tabla' => 'Usuario',
                'cabecera' => 'Eliminar usuario',
                'usuario' => session::get('usuario_nombre'),
                'accion' => 'eliminar_accion',
                'id' => $id_usuario,
                'zona_usuario' => $zona['nombrezona'],
                'hora' => session::get('usuario_hora_inicio'),
                'zonas' => $zonas
            ));
        }
    }

    /**
     * ACCIÓN: Cerrar sesión del usuario, borramos cookie y sesion
     */
    public function logout()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // eliminamos cookie según explica el enlace de abajo
        // ponemos una fecha antigua.
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', 'localhost');
        
        // borramos sesion
        Session::destroy();
        
        // redireccionamos al formulario de login
        header('location: ' . URL . 'login');
    }

    /**
     * ACCIÓN: borrar parámetros de filtro guardados en la sesión
     */
    public function borraFiltros()
    {
        Session::start();
        Session::_unset('filtro_texto');
        
        // Redireccionamos al listado de envíos
        header('location: ' . URL . 'usuario/index');
    }

    /**
     * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formEditUsuario()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formEnvio = array(
            'username' => 'alfanum',
            'email' => 'email'
        );
        
        return $formEnvio;
    }

    /**
     * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formAddUsuario()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formEnvio = array(
            'username' => 'alfanum',
            'email' => 'email',
            'password_hash' => 'alfanum'
        );
        
        return $formEnvio;
    }

    /**
     * Rellena el array con campos esperados del formulario configuracion -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formConfiguracion()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formConfig = array(
            'REGS_PAG' => 'numerico',
            'COOKIE_RUNTIME' => 'numerico'
        );
        
        return $formConfig;
    }

    /**
     * Rellena el array con campos esperados del formulario filtrar -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formFiltro()
    {
        $formFiltrar = array(
            'texto' => 'alfanum'
        );
        
        return $formFiltrar;
    }
}