<?php

/**
 * Clase controller
 * 
 * Este será el controlador base, que heredarán el resto de controladores 
 * Desde aquí cargaremos el modelo requerido y renderizamos la vista 
 * 
 * @author Carlos * 
 * @version 0.1
 */
class Controller
{
    public function __construct()
    {
    	Autorizacion::checkLogin();
    	
        Session::start();
        // Si existe cookie, intentamos hacer login con la cookie
        if (! isset($_SESSION['usuario_logueado']) && isset($_COOKIE['rememberme'])) {
            header('location: ' . URL . 'login/loginConCookie');
        }
    }

    /**
     * Carga el modelo requerido.
     *
     *
     * @param string $model_name
     *            El nombre del modelo a cargar
     * @return object modelo
     */
    public function loadModel($model_name)
    {
        // construyo cadena y cargo el modelo
        require 'app/models/' . strtolower($model_name) . '.php';
        // return el modelo cargado
        return new $model_name();
    }

    /**
     * Función que carga la plantilla twig y genera la vista
     *
     * @param string $view            
     * @param array $data_array            
     */
    public function render($view, $data_array = array())
    {
        // TODO cargar twig autoloader
        
        // Cargamos el motor de plantillas Twig
        $twig_loader = new Twig_Loader_Filesystem(PATH_VIEWS);
        $twig = new Twig_Environment($twig_loader);
        
        // Genera la vista
        echo $twig->render($view . PATH_VIEW_FILE_TYPE, $data_array);
    }

    /**
     *
     * @return Ambigous <multitype:string , multitype:unknown valor >
     */
    public function filtraFormulario($formEnvio)
    {
        require 'app/libs/form_Validation.php';
        $validate = new Validation();
        $data = $validate->checkForm($formEnvio);
        return $data;
    }

    /**
     * Validación formularios filtrados y sanitizados
     *
     * @param unknown $data            
     */
    public function validation($data)
    {
        return empty($data['errores']);
    }

    /**
     *
     * @param unknown $pag            
     * @param unknown $totalRows            
     * @return multitype:number
     */
    public function pagination($pag, $totalRows)
    {
        if ($pag < 1)
            $pag = 1;
        
        $offset = ($pag - 1) * REGS_PAG;
        $totalPag = ceil($totalRows / REGS_PAG);
        
        $inicio = max($pag - 1, 1);
        
        $fin = min($pag + 3, $totalPag);
        
        if ($inicio == 1 && ($fin - $inicio < 3))
            $fin = $inicio + 4;
        
        if ($inicio == $totalPag - 1 || $inicio == $totalPag - 2 && ($fin - $inicio < 3))
            $inicio = $fin - 4;
        
        $data = array(
            'pag' => $pag,
            'offset' => $offset,
            'totalPag' => $totalPag,
            'inicio' => $inicio,
            'fin' => $fin
        );
        
        return $data;
    }
}