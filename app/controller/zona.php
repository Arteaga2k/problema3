<?php

class Zona extends Controller
{

    /**
     * PÁGINA: index
     *
     * http://problema1/zona/index
     */
    public function index($pag = 1)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $zona_model = $this->loadModel('ZonaModel');
        $zonas = $zona_model->getZonas();
        $zona = $zona_model->getZona(session::get('usuario_zona'));
        
        // comprueba si existe en sesion variables campos para filtrar busqueda
        $filtro = $this->compruebaFiltro();
        $totalRows = $zona_model->getTotalRows($filtro);
        $pagination = $this->pagination($pag, $totalRows);
        
        $zonasPag = $zona_model->getZonasPag(isset($filtro) ? $filtro : null, $pagination['offset'], null);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('zonas/index', array(
            'tabla' => 'Zona',
            'cabecera' => 'Lista zonas administrativas',
            'usuario' => session::get('usuario_nombre'),
            'zonas' => $zonas,
            'zonasPag' => $zonasPag,
            'zona_usuario' => $zona['nombrezona'],
            'hora' => Session::get('usuario_hora_inicio'),
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
        
        header('location: ' . URL . 'zona');
    }

    /**
     * ACCIÓN: borrar parámetros de filtro guardados en la sesión
     */
    public function borraFiltros()
    {
        Session::start();
        Session::_unset('filtro_texto');
        
        // Redireccionamos al listado de envíos
        header('location: ' . URL . 'zona/index');
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
     * PÁGINA: add
     *
     * http://problema1/zona/add
     */
    public function add()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $zona_model = $this->loadModel('ZonaModel');
        $zonas = $zona_model->getZonas();
        $zona = $zona_model->getZona(session::get('usuario_zona'));
        
        // Mostramos datos del envío a editar
        $this->render('zonas/form_zona', array(
            'tabla' => 'Zona',
            'cabecera' => 'Añadir zona',
            'usuario' => session::get('usuario_nombre'),
            'accion' => 'add_accion',
            'zonas' => $zonas,
            'zona_usuario' => $zona['nombrezona'],
            'hora' => Session::get('usuario_hora_inicio')
        ));
    }

    /**
     * ACCIÓN: Añadir una nueva zona
     *
     * recoge campos del formulario add_zona
     */
    public function add_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['add_accion'])) {
            
            // cargamos modelo, realizamos accion, guardamos resultado en una variable
            $zona_model = $this->loadModel('ZonaModel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona($_SESSION['usuario_zona']);
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formZonas());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $zona_model->addZona($data['datos']);
                header('location: ' . URL . 'zona');
            } else {
                $this->render('zonas/form_zona', array(
                    'tabla' => 'Zona',
                    'cabecera' => 'Añadir zona',
                    'usuario' => session::get('usuario_nombre'),
                    'accion' => 'add_accion',
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],
                    'zonas' => $zonas,
                    'zona_usuario' => $zona['nombrezona'],
                    'hora' => Session::get('usuario_hora_inicio')
                ));
            }
        }
    }

    /**
     * PÁGINA: editar zona
     *
     * http://problema1/zona/editar/id
     *
     * @param int $zona_id
     *            identificador tabla envío
     */
    public function editar($zona_id = NULL)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $zona_id
        if (isset($zona_id)) {
            // cargamos modelo, realizamos accion, guardamos resultado en una variable
            $zona_model = $this->loadModel('ZonaModel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona($_SESSION['usuario_zona']);
            
            // obtenemos datos del envío a editar
            $data['datos'] = $zona_model->getZona($zona_id);
            
            // Mostramos datos del envío a editar
            $this->render('zonas/form_zona', array(
                'tabla' => 'Zona',
                'cabecera' => 'Editar zona',
                'usuario' => session::get('usuario_nombre'),
                'accion' => 'editar_accion',
                'datos' => $data['datos'],
                'id_zona' => $zona_id,
                'zonas' => $zonas,
                'zona_usuario' => $zona['nombrezona'],
                'hora' => Session::get('usuario_hora_inicio')
            ));
        }
    }

    /**
     * ACCIÓN: editar un envío
     *
     * recoge campos del formulario edición envio
     */
    public function editar_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // Llamada desde formulario editar para guardar cambios
        if (isset($_REQUEST['editar_accion'])) {
            // cargamos modelo, realizamos accion, guardamos resultado en una variable
            $zona_model = $this->loadModel('ZonaModel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona($_SESSION['usuario_zona']);
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formZonas());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $zona_model->editZona($data['datos'], $_REQUEST['id_zona']);
                header('location: ' . URL . 'zona/index');
            } else {
                $this->render('zonas/form_zona', array(
                    'tabla' => 'Zona',
                    'cabecera' => 'Editar zona',
                    'usuario' => session::get('usuario_nombre'),
                    'accion' => 'editar_accion',
                    'id_zona' => $_REQUEST['id_zona'],
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],
                    'zonas' => $zonas,
                    'zona_usuario' => $zona['nombrezona'],
                    'hora' => Session::get('usuario_hora_inicio')
                ));
            }
        } else {
            // Redireccionamos al listado de envíos
            header('location: ' . URL . 'zona/index');
        }
    }

    /**
     * PÁGINA: eliminar zona
     *
     * http://problema1/_templates/confirmacion/
     *
     * @param int $id_zona
     *            identificador tabla zona
     */
    public function eliminar($id_zona)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $envio_id
        if (isset($id_zona)) {
            // cargamos modelo, realizamos accion, guardamos resultado en una variable
            $zona_model = $this->loadModel('ZonaModel');
            $zonas = $zona_model->getZonas();
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            
            $this->render('_templates/confirmacion', array(
                'tabla' => 'Zona',
                'cabecera' => 'Eliminar zona',
                'usuario' => session::get('usuario_nombre'),
                'accion' => 'eliminar_accion',
                'id' => $id_zona,
                'zonas' => $zonas,
                'zona_usuario' => $zona['nombrezona'],
                'hora' => Session::get('usuario_hora_inicio')
            ));
        }
    }

    /**
     * ACCIÓN: eliminar zona
     *
     * http://problema1/zona/eliminar/id
     *
     * @param int $envio_id
     *            identificador tabla zona
     */
    public function eliminar_accion($id_zona)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $zona_model = $this->loadModel('ZonaModel');
        $zonas = $zona_model->getZonas();
        $zona = $zona_model->getZona(session::get('usuario_zona'));
        // realizamos la acción
        $confirmacion = $zona_model->deleteZona($id_zona);
        
        if ($confirmacion) {
            // Mostramos el listado de envios refrescado
            header('location: ' . URL . 'zona');
        } else {
            $this->render('_templates/error', array(
                'tabla' => 'Zona',
                'cabecera' => 'Eliminar zona',
                'usuario' => $_SESSION['usuario_nombre'],
                'mensaje' => 'No se puede elimnar este registro',
                'zonas' => $zonas,
                'zona_usuario' => $zona['nombrezona'],
                'hora' => Session::get('usuario_hora_inicio')
            ));
        }
    }

    /**
     * ACCION: cambiar zona de recepcion
     */
    public function cambiaZona($id_zona)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // Guardamos el cambio de zona en la sesion
        Session::set('usuario_zona', $id_zona);
        // redireccionamos a la pagina principal
        header('location: ' . URL . 'home/index');
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
                $id_zona = $data['datos']['ver'];
                
                // cargamos modelo, realizamos accion, guardamos resultado en una variable
                $zona_model = $this->loadModel('ZonaModel');
                $zonas = $zona_model->getZonas();
                $zona = $zona_model->getZona($_SESSION['usuario_zona']);
                // obtenemos datos del envío a editar
                $data['datos'] = $zona_model->getZona($id_zona);
                
                if (! empty($data['datos'])) {
                    $this->render('zonas/form_zona', array(
                        'tabla' => 'Zona',
                        'usuario' => session::get('usuario_nombre'),
                        'cabecera' => 'Consultar zona',
                        'accion' => 'consulta',
                        'datos' => $data['datos'],
                        'zonas' => $zonas,
                        'zona_usuario' => $zona['nombrezona'],
                        'hora' => session::get('usuario_hora_inicio')
                    ));
                } else {
                    header('location: ' . URL . 'zona');
                }
            } else {
                header('location: ' . URL . 'zona');
            }
        }
    }

    /**
     * Rellena el array con campos esperados del formulario envío -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formZonas()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formZona = array(
            'nombrezona' => 'alfanum'
        );
        
        return $formZona;
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