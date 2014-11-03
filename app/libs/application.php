<?php

/**
 *  * @author Carlos
 *  Controlador frontal
 *  Analizar� la url e invocar� el controlador determinado por la url
 *
 */
class Application
{

    /**
     *
     * @var Nombre del controlador a cargar
     */
    private $url_controller = null;

    /**
     *
     * @var Nombre del m�todo a cargar
     */
    private $url_action = null;

    /**
     *
     * @var primer par�metro del m�todo
     */
    private $url_parameter_1 = null;

    /**
     *
     * @var segundo par�metro del m�todo
     */
    private $url_parameter_2 = null;

    /**
     *
     * @var tercer par�metro del m�todo
     */
    private $url_parameter_3 = null;

    /**
     * "Ejecuta el programa":
     * Analiza la URL y llama a su m�todo correspondiente si existe tal m�todo
     */
    public function __construct()
    {
        // crea un array con los par�metros de la URL de la variable $url
        $this->separaUrl();
        
        // comprobamos que el archivo controlador existe
        if (file_exists('./app/controller/' . $this->url_controller . '.php')) {
            
            // Si existe cargamos el archivo y creamos el controlador
            require './app/controller/' . $this->url_controller . '.php';
            
            $this->url_controller = new $this->url_controller();
            
            // comprobamos que el m�todo requerido existe en el controlador cargado
            if (method_exists($this->url_controller, $this->url_action)) {
                
                // ejecutamos el m�todo(accion) y pasamos los par�metros
                if (isset($this->url_parameter_3)) {
                    // $this->controlador->metodo($param_1, $param_2, $param_3);
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2, $this->url_parameter_3);
                } elseif (isset($this->url_parameter_2)) {
                    // $this->controlador->metodo($param_1, $param_2);
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
                } elseif (isset($this->url_parameter_1)) {
                    // $this->controlador->metodo($param_1);
                    $this->url_controller->{$this->url_action}($this->url_parameter_1);
                } else {
                    // Si no hay par�metros, ejecutamos el m�todo sin par�metros por defecto
                    $this->url_controller->{$this->url_action}();
                }
            } else {
                // si no existe el m�todo llamamos al index del controlador por defecto
                $this->url_controller->index();
            }
        } else {
            // Si no existe o es inv�lida la URL nos vamos al index de inicio
            require './app/controller/home.php';
            $home = new Home();
            $home->index();
        }
    }

    /**
     * Analiza y separa la URL que recibe la aplicaci�n
     */
    private function separaUrl()
    {
        if (isset($_GET['url'])) {
            
            // separamos la URL
            $url = rtrim($_GET['url'], '/'); // Retiramos los espacios en blanco del final de un string
            $url = filter_var($url, FILTER_SANITIZE_URL); // Eliminamos caracteres especiales
            $url = explode('/', $url); // Devuelve un array de strings
                                       
            // Guardamos los par�metros recibidos por la URL
                                       // en el siguiente orden: ../controlador/accion/parametros
            $this->url_controller = (isset($url[0]) ? $url[0] : null);
            $this->url_action = (isset($url[1]) ? $url[1] : null);
            $this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
            $this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
            $this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);
            
            echo 'Controlador: ' . $this->url_controller . '<br />';
            echo 'Accion: ' . $this->url_action . '<br />';
            echo 'Parametro 1: ' . $this->url_parameter_1 . '<br />';
            echo 'Parametro 2: ' . $this->url_parameter_2 . '<br />';
            echo 'Paramero 3: ' . $this->url_parameter_3 . '<br />';
        }
    }
}