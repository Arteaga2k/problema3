<?php

/**
 * CLASE envios 
 * 
 * Controlador para la tabla envios, operaciones crud
 *  
 * @author Carlos 
 */
class Envios extends Controller
{

    /**
     * PÁGINA: index
     *
     * http://problema1/envios/index
     */
    public function index($pag = 1)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // comprueba si existe en sesion variables campos para filtrar busqueda
        $filtro = $this->compruebaFiltro();
        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $envios_model = $this->loadModel('EnviosModel');
        $zona_model = $this->loadModel('ZonaModel');
        
        $totalRows = $envios_model->getTotalRows($filtro, $_SESSION['usuario_zona']);
        
        $pagination = $this->pagination($pag, $totalRows);
        
        $envios = $envios_model->getPagEnvios(isset($filtro) ? $filtro : null, $pagination['offset'], null, $_SESSION['usuario_zona']);
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        $zonas = $zona_model->getZonas();
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('envios/index', array(
            'tabla' => 'Envios',
            'cabecera' => 'Lista envíos',
            'usuario' => session::get('usuario_nombre'),
            'hora' => Session::get('usuario_hora_inicio'),
            'envios' => $envios,
            'page' => $pagination['pag'],
            'totalpag' => $pagination['totalPag'],
            'inicio' => $pagination['inicio'],
            'fin' => $pagination['fin'],
            'filtro' => isset($filtro) ? $filtro : '',
            'zona_usuario' => $zona['nombrezona'],
            'zonas' => $zonas,
            'avatar' => session::get('AVATAR'),
            'tema' => Session::get('TEMA')
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
                if ($value && empty($data['errores'][$key])) {                  
                    Session::set('filtro_' . $key, $value);
                }
            }
        }
        
        header('location: ' . URL . 'envios');
    }

    /**
     * ACCIÓN: borrar parámetros de filtro guardados en la sesión
     */
    public function borraFiltros()
    {
        Session::start();
        Session::_unset('filtro_texto');
        Session::_unset('filtro_fec_desde');
        Session::_unset('filtro_fec_hasta');
        
        // Redireccionamos al listado de envíos
        header('location: ' . URL . 'envios/index');
    }

    /**
     * PÁGINA: añadir envío
     *
     * http://problema1/envios/add
     */
    public function add()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos el modelo y realizamos la acción
        $envios_model = $this->loadModel('EnviosModel');
        $zona_model = $this->loadModel('Zonamodel');
        
        // obtenemos listado provincias a mostrar
        $provincias = $envios_model->getAllProvincias();
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        $zonas = $zona_model->getZonas();
        
        // Cargamos formulario alta envío
        $this->render('envios/form_envio', array(
            'tabla' => 'Envios',
            'cabecera' => 'Añadir envío',
            'usuario' => session::get('usuario_nombre'),
            'hora' => session::get('usuario_hora_inicio'),
            'accion' => 'add_accion',
            'provincias' => $provincias,
            'zona_usuario' => $zona['nombrezona'],
            'zonas' => $zonas,
            'avatar' => session::get('AVATAR'),
            'tema' => Session::get('TEMA')
        ));
    }

    /**
     * ACCIÓN: Añadir un nuevo envío
     *
     * recoge campos del formulario add envio
     */
    public function add_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['add_accion'])) {
            // cargamos el modelo y realizamos la acción
            $envios_model = $this->loadModel('EnviosModel');
            // obtenemos listado provincias a mostrar
            $provincias = $envios_model->getAllProvincias();
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formAddEnvio());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $envios_model->addEnvio($data['datos']);
                header('location: ' . URL . 'envios');
            } else {
                $zona_model = $this->loadModel('Zonamodel');
                $zona = $zona_model->getZona($_SESSION['usuario_zona']);
                $zonas = $zona_model->getZonas();
                
                $this->render('envios/form_envio', array(
                    'cabecera' => 'Añadir envío',
                    'usuario' => $_SESSION['usuario_nombre'],
                    'accion' => 'add_accion',
                    'provincias' => $provincias,
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],
                    'zona_usuario' => $zona['nombrezona'],
                    'zonas' => $zonas,
                    'avatar' => session::get('AVATAR'),
                    'tema' => Session::get('TEMA')
                ));
            }
        }
    }

    /**
     * ACCIÓN: eliminar envío
     *
     * http://problema1/envios/eliminar/id
     *
     * @param int $envio_id
     *            identificador tabla envío
     */
    public function eliminar($envio_id)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $envio_id
        if (isset($envio_id)) {
            $zona_model = $this->loadModel('Zonamodel');
            $zona = $zona_model->getZona($_SESSION['usuario_zona']);
            $zonas = $zona_model->getZonas();
            
            $this->render('_templates/confirmacion', array(
                'tabla' => 'Envios',
                'cabecera' => 'Eliminar envio',
                'usuario' => session::get('usuario_nombre'),
                'hora' => session::get('usuario_hora_inicio'),
                'zona_usuario' => $zona['nombrezona'],
                'zonas' => $zonas,
                'accion' => 'eliminar_accion',
                'id' => $envio_id,
                'avatar' => session::get('AVATAR'),
                'tema' => Session::get('TEMA')
            ));
        }
    }

    /**
     * ACCIÓN: eliminar envio
     *
     * http://problema1/envios/eliminar/id
     *
     * @param int $envio_id
     *            identificador tabla zona
     */
    public function eliminar_accion($envio_id)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // cargamos el modelo
        $envios_model = $this->loadModel('EnviosModel');
        // realizamos la acción
        $confirmacion = $envios_model->deleteEnvio($envio_id);
        
        if ($confirmacion) {
            // Mostramos el listado de envios refrescado
            header('location: ' . URL . 'Envios');
        } else {
            $this->render('_templates/error', array(
                'tabla' => 'envios',
                'cabecera' => 'Eliminar envio',
                'usuario' => $_SESSION['usuario_nombre'],
                'mensaje' => 'No se puede elimnar este registro',
                'avatar' => session::get('AVATAR'),
                'tema' => Session::get('TEMA')
            ));
        }
    }

    /**
     * PÁGINA: editar envío
     *
     * http://problema1/envios/editar/id
     *
     * @param int $envio_id
     *            identificador tabla envío
     */
    public function editar($envio_id = NULL)
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        // si existe $envio_id
        if (isset($envio_id)) {
            // cargamos modelos
            $envios_model = $this->loadModel('EnviosModel');
            $zona_model = $this->loadModel('ZonaModel');
            // obtenemos datos del envío a editar
            $data['datos'] = $envios_model->getEnvio($envio_id, session::get('usuario_zona'));
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            $zonas = $zona_model->getZonas();
            
            // obtenemos listado de provincias
            $provincias = $envios_model->getAllProvincias();
            
            // Mostramos datos del envío a editar
            $this->render('envios/form_envio', array(
                'tabla' => 'Envios',
                'cabecera' => 'Editar envío',
                'usuario' => session::get('usuario_nombre'),
                'hora' => session::get('usuario_hora_inicio'),
                'accion' => 'editar_accion',
                'datos' => $data['datos'],
                'id_envio' => $envio_id,
                'provincias' => $provincias,
                'zona_usuario' => $zona['nombrezona'],
                'zonas' => $zonas,
                'avatar' => session::get('AVATAR'),
                'tema' => Session::get('TEMA')
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
            // cargamos modelos
            $envios_model = $this->loadModel('EnviosModel');
            $zona_model = $this->loadModel('ZonaModel');
            // obtenemos datos
            $provincias = $envios_model->getAllProvincias();
            $zona = $zona_model->getZona($_SESSION['usuario_zona']);
            $zonas = $zona_model->getZonas();
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formAddEnvio());
           
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $envios_model->editEnvio($data['datos'], $_REQUEST['id_envio']);
                header('location: ' . URL . 'envios/index');
            } else {
                $this->render('envios/form_envio', array(
                    'tabla' => 'Envios',
                    'cabecera' => 'Editar envío',
                    'usuario' => session::get('usuario_nombre'),
                    'provincias' => $provincias,
                    'accion' => 'editar_accion',
                    'id_envio' => $_REQUEST['id_envio'],
                    'datos' => $data['datos'],
                    'errores' => $data['errores'],
                    'zona_usuario' => $zona['nombrezona'],
                    'zonas' => $zonas,
                    'avatar' => session::get('AVATAR'),
                    'tema' => Session::get('TEMA')
                ));
            }
        } else {
            // Redireccionamos al listado de envíos
            header('location: ' . URL . 'envios/index');
        }
    }

    /**
     * PÁGINA: anotar envío
     *
     * http://problema1/envios/annotate/id
     *
     * @param int $envio_id
     *            identificador tabla envío
     */
    public function anotar($envio_id = NULL)
    { 
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (! is_null($envio_id)) {
            $envios_model = $this->loadModel('EnviosModel');
            $zona_model = $this->loadModel('Zonamodel');
            // obtenemos datos del envío a editar
            $data['datos'] = $envios_model->getEnvio($envio_id, $_SESSION['usuario_zona']);
            // obtenemos listado de provincias
            $provincias = $envios_model->getAllProvincias();
            
            $zona = $zona_model->getZona(session::get('usuario_zona'));
            $zonas = $zona_model->getZonas();
            
            $this->render('envios/form_envio', array(
                'tabla' => 'Envios',
                'cabecera' => 'Anotar envío',
                'usuario' => session::get('usuario_nombre'),
                'hora' => session::get('usuario_hora_inicio'),
                'accion' => 'anotar_accion',
                'provincias' => $provincias,
                'datos' => $data['datos'],
                'zona_usuario' => $zona['nombrezona'],
                'zonas' => $zonas,
                'avatar' => session::get('AVATAR'),
                'tema' => Session::get('TEMA')
            ));
        }
    }

    /**
     * ACCIÓN: anotar un envío
     *
     * recoge campos del formulario anotar envio y lo anota
     */
    public function anotar_accion()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['anotar_accion'])) {
            // cargamos el modelo
            $envios_model = $this->loadModel('EnviosModel');
            // filtramos y sanitizamos formulario
            $dataAnotar = $this->filtraFormulario($this->formAnotarEnvio());
            
            if (empty($dataAnotar['errores']) && isset($_REQUEST['confirmacion'])) {
                // Anotamos el envío
                $envios_model->anotaEnvio($dataAnotar['datos'], $_REQUEST['id_envio']);
                // Redireccionamos al listado de envíos
                header('location: ' . URL . 'envios/index');
            } else {
                // obtenemos datos del envío a editar y añadimos a datos del usuario que estamos editando
                $data['datos'] = $envios_model->getEnvio($_REQUEST['id_envio'], session::get('usuario_zona'));
                foreach ($dataAnotar['datos'] as $key => $value) {
                    $data['datos'][$key] = $value;
                }
                if (! isset($_REQUEST['confirmacion'])) {
                    $dataAnotar['errores']['confirmacion'] = 'tiene que confirmar';
                }
                $zona_model = $this->loadModel('Zonamodel');
                $zona = $zona_model->getZona(session::get('usuario_zona'));
                $zonas = $zona_model->getZonas();
                
                // obtenemos listado de provincias
                $provincias = $envios_model->getAllProvincias();
                
                $this->render('envios/form_envio', array(
                    'tabla' => 'Envios',
                    'cabecera' => 'Anotar envío',
                    'usuario' => $_SESSION['usuario_nombre'],
                    'accion' => 'anotar_accion',
                    'provincias' => $provincias,
                    'id_envio' => $_REQUEST['id_envio'],
                    'errores' => $dataAnotar['errores'],
                    'datos' => $data['datos'],
                    'zona_usuario' => $zona['nombrezona'],
                    'zonas' => $zonas,
                    'hora' => session::get('usuario_hora_inicio'),
                    'avatar' => session::get('AVATAR'),
                    'tema' => Session::get('TEMA')
                ));
            }
        }
    }

    /**
     * ACCIÓN: consultar envío
     *
     * http://problema1/envios/consulta
     */
    public function consulta()
    {
        // estamos logueado?
        Autorizacion::checkLogin();
        
        if (isset($_REQUEST['consulta'])) {
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario(array(
                'ver' => 'consulta'
            ));
            
            if ($this->validation($data)) {
                $id_envio = $data['datos']['ver'];
                // cargamos el modelo
                $envios_model = $this->loadModel('EnviosModel');
                // obtenemos datos del envío a editar
                $data['datos'] = $envios_model->getEnvio($id_envio, $_SESSION['usuario_zona']);
                // obtenemos listado de provincias
                $provincias = $envios_model->getAllProvincias();
                $zona_model = $this->loadModel('Zonamodel');
                $zona = $zona_model->getZona(session::get('usuario_zona'));
                $zonas = $zona_model->getZonas();
                
                if (! empty($data['datos'])) {
                    $this->render('envios/form_envio', array(
                        'tabla' => 'Envios',
                        'usuario' => $_SESSION['usuario_nombre'],
                        'cabecera' => 'Consultar envío',
                        'accion' => 'consulta',
                        'provincias' => $provincias,
                        'datos' => $data['datos'],
                        'tema' => Session::get('TEMA'),
                        'hora' => session::get('usuario_hora_inicio'),
                        'avatar' => session::get('AVATAR'),
                        'zona_usuario' => $zona['nombrezona'],
                        'zonas' => $zonas
                    ));
                } else {
                    header('location: ' . URL . 'envios');
                }
            } else {
                header('location: ' . URL . 'envios');
            }
        }
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
        if (isset($_SESSION['filtro_fec_desde'])) {
            $filtro['filtro_fec_desde'] = $_SESSION['filtro_fec_desde'];
        }
        if (isset($_SESSION['filtro_fec_hasta'])) {
            $filtro['filtro_fec_hasta'] = $_SESSION['filtro_fec_hasta'];
        }
        
        return $filtro;
    }

    /**
     * Rellena el array con campos esperados del formulario envío -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formAddEnvio()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formEnvio = array(
            'direccion' => 'alfanum',
            'poblacion' => 'texto',
            'codpostal' => 'codpostal',
            'provincia' => 'numerico',
            'email' => 'email',
            'estado' => 'texto',
            'fec_creacion' => 'date',
            'observaciones' => 'richtext',
            'nombre' => 'texto',
            'apellido1' => 'texto',
            'apellido2' => 'texto',
            'razonsocial' => 'alfanum',
            'telefono1' => 'telefono',
            'telefono2' => 'telefono',
            'zona_entrega' => 'numerico',
            'zona_recepcion' => 'numerico'
        );
        
        return $formEnvio;
    }

    /**
     *
     * @return multitype:string
     */
    public function formAnotarEnvio()
    {
        $formAnotar = array(
            'observaciones' => 'richtext',
            'fec_entrega' => 'date'
        );
        
        return $formAnotar;
    }

    /**
     * Rellena el array con campos esperados del formulario filtrar -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formFiltro()
    {
        $formFiltrar = array(
            'texto' => 'alfanum',
            'fec_desde' => 'date',
            'fec_hasta' => 'date'
        );
        
        return $formFiltrar;
    }
}