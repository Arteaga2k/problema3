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
    	Autorizacion::checkLogin();
    	
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $envios_model = $this->loadModel('EnviosModel');
        $totalRows = $envios_model->getTotalRows();
        
        $pagination = $this->pagination($pag, $totalRows);
        
        $envios = $envios_model->getAllEnvios(null, $pagination['offset']);
        
        kint::dump($envios);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('envios/index', array(
            'title' => 'Lista envíos',
            'envios' => $envios,
            'page' => $pagination['pag'],
            'totalpag' => $pagination['totalPag'],
            'inicio' => $pagination['inicio'],
            'fin' => $pagination['fin']
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
            
            Session::start();
            
            foreach ($data['datos'] as $key => $value) {
                if ($value) {
                    Session::set('filtro_' . $key, $value);
                }
            }
        }
        header('location: ' . URL . 'envios');
    }

    /**
     * ACCIÓN: borrar parámetros de filtro guardados en sesión
     */
    public function borraFiltros()
    {
        Session::start();
        Session::_unset('filtro_texto');
        Session::_unset('filtro_fec_desde');
        Session::_unset('filtro_fec_hasta');
    }

    /**
     * PÁGINA: añadir envío
     *
     * http://problema1/envios/add
     */
    public function add()
    {
        // cargamos el modelo y realizamos la acción
        $envios_model = $this->loadModel('EnviosModel');
        // obtenemos listado provincias a mostrar
        $provincias = $envios_model->getAllProvincias();
        
        // Cargamos formulario alta envío
        $this->render('envios/form_envio', array(
            'title' => 'Añadir envío',
            'accion' => 'add_accion',
            'provincias' => $provincias
        ));
    }

    /**
     * ACCIÓN: Añadir un nuevo envío
     *
     * recoge campos del formulario add envio
     */
    public function add_accion()
    {
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
                $this->render('envios/form_envio', array(
                    'title' => 'Añadir envío',
                    'accion' => 'add_accion',
                    'provincias' => $provincias,
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
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
        // si existe $envio_id
        if (isset($envio_id)) {
            // cargamos el modelo
            $envios_model = $this->loadModel('EnviosModel');
            // realizamos la acción
            $envios_model->deleteEnvio($envio_id);
        }
        
        // Mostramos el listado de envios refrescado
        header('location: ' . URL . 'envios');
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
        // si existe $envio_id
        if (isset($envio_id)) {
            // cargamos el modelo
            $envios_model = $this->loadModel('EnviosModel');
            // obtenemos datos del envío a editar
            $data['datos'] = $envios_model->getEnvio($envio_id);
            
            // obtenemos listado de provincias
            $provincias = $envios_model->getAllProvincias();
            
            // Mostramos datos del envío a editar
            $this->render('envios/form_envio', array(
                'title' => 'Editar envío',
                'accion' => 'editar_accion',
                'datos' => $data['datos'],
                'id_envio' => $envio_id,
                'provincias' => $provincias
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
        
        // Llamada desde formulario editar para guardar cambios
        if (isset($_REQUEST['editar_accion'])) {
            // cargamos el modelo y realizamos la acción
            $envios_model = $this->loadModel('EnviosModel');
            // obtenemos listado provincias a mostrar
            $provincias = $envios_model->getAllProvincias();
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formAddEnvio());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $envios_model->editEnvio($data['datos'], $_REQUEST['id_envio']);
                header('location: ' . URL . 'envios/index');
            } else {
                $this->render('envios/form_envio', array(
                    'title' => 'Editar envío',
                    'provincias' => $provincias,
                    'accion' => 'editar_accion',
                    'id_envio' => $_REQUEST['id_envio'],
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
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
        if (! is_null($envio_id)) {
            $envios_model = $this->loadModel('EnviosModel');
            // obtenemos datos del envío a editar
            $data['datos'] = $envios_model->getEnvio($envio_id);
            // obtenemos listado de provincias
            $provincias = $envios_model->getAllProvincias();
            
            $this->render('envios/form_envio', array(
                'title' => 'Anotar envío',
                'accion' => 'anota_accionr',
                'provincias' => $provincias,
                'datos' => $data['datos']
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
        if (isset($_REQUEST['anotar'])) {
            
            // filtramos y sanitizamos formulario
            $dataAnotar = $this->filtraFormulario($this->formAnotarEnvio());
            
            // Guardamos los errores del formulario anotar con el array datos del envío a editar
            foreach ($dataAnotar['datos'] as $key => $value) {
                $data['errores'] = $dataAnotar['datos'];
            }
            
            if (empty($dataAnotar['errores']) && isset($_REQUEST['confirmacion'])) {
                // Anotamos el envío
                $envios_model->anotaEnvio($dataAnotar['datos'], $envio_id);
                // Redireccionamos al listado de envíos
                header('location: ' . URL . 'envios/index');
            } else {
                // $data = array_merge($data, $dataAnotar);
                // var_dump($data);
                $this->render('envios/form_envio', array(
                    'title' => 'Anotar envío',
                    'accion' => 'anota_accionr',
                    'provincias' => $provincias,
                    'errores' => $dataAnotar['errores'],
                    'datos' => $data['datos']
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
        if (isset($_REQUEST['consulta'])) {
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario(array(
                'ver' => 'numerico'
            ));
            
            if ($this->validation($data)) {
                $id_envio = $data['datos']['ver'];
                // cargamos el modelo
                $envios_model = $this->loadModel('EnviosModel');
                // obtenemos datos del envío a editar
                $data['datos'] = $envios_model->getEnvio($id_envio);
                // obtenemos listado de provincias
                $provincias = $envios_model->getAllProvincias();
                
                if (! empty($data['datos'])) {
                    $this->render('envios/form_envio', array(
                        'title' => 'Consultar envío',
                        'accion' => 'consulta',
                        'provincias' => $provincias,
                        'datos' => $data['datos']
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
            'telefono1' => 'numerico',
            'telefono2' => 'numerico'
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
            'fec_entrega' => 'date',
            'estado' => 'texto'
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