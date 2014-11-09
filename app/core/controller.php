<?php

/**
 * @author Carlos
 * Este ser� el controlador base, que heredar�n el resto de controladores 
 * Desde aqu� cargaremos el modelo requerido y renderizamos la vista 
 */
class Controller
{

    /**
     *
     * @var unknown
     */
    protected $_errores;

    /**
     * Carga el modelo requerido.
     * Note that the model class name is written in "CamelCase", the model's filename is the same in lowercase letters
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
     * Filtra el valor de una cadena recibida y guarda errores
     *
     * @param String $cadena            
     * @param String $tipo            
     * @return mixed|boolean
     */
    public function filterValue($cadena, $tipo, $campo)
    {
        
        /*
         * Expresiones regulares
         *
         * "/^[0-9]*$/"; // Solo cadena vacia o numeros
         *
         * "/^[0-9]+$/"; // Solo numeros. No se admite cadena vacia
         *
         * "/^[a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas. No se admite cadena vacia
         *
         * "/^[0-9a-zA-Z]+$/"; // Solo letras en mayusculas/minusculas y numeros. No se admite cadena vacia
         *
         * "#^[a-z0-9\s]+$#i"; // Alfanuméricos y espacio. No se admite cadena vacia
         * mm/dd/aaa // Formato para funcion checkdate
         */
        
        // Comprobamos que la cadena no está vacía
        if ($cadena) {
            if ($tipo == "texto") {
                
                // Valida cadena de texto
                if (filter_var($cadena, FILTER_VALIDATE_REGEXP, array(
                    "options" => array(
                        "regexp" => "/^[a-zA-Z]+$/"
                    )
                ))) {
                    $this->_errores[$campo] = '';
                } else {
                    $this->_errores[$campo] = 'El ' . $campo . ' no es válido';
                }
            } elseif ($tipo == "numerico") {
                
                // Valida cadena de texto
                if (filter_var($cadena, FILTER_VALIDATE_REGEXP, array(
                    "options" => array(
                        "regexp" => "/^[0-9]+$/"
                    )
                ))) {
                    $this->_errores[$campo] = '';
                } else {
                    $this->_errores[$campo] = 'El ' . $campo . ' no es válido';
                }
            } elseif ($tipo == "alfanum") {
                
                // Valida cadena de texto
                if (filter_var($cadena, FILTER_VALIDATE_REGEXP, array(
                    "options" => array(
                        "regexp" => "#^[a-z0-9\s]+$#i"
                    )
                ))) {
                    $this->_errores[$campo] = '';
                } else {
                    $this->_errores[$campo] = 'El ' . $campo . ' no es válido';
                }
            } elseif ($tipo == "date") {
                $fecha = explode("/", $cadena);
                
                if (count($fecha) == 3){
                    // Valida cadena de texto
                    if (checkdate($fecha[0], $fecha[1], $fecha[2])) {
                        $this->_errores[$campo] = '';
                    } else {
                        $this->_errores[$campo] = "La fecha no es válida";
                    }
                }else{
                    $this->_errores[$campo] = "Formato de fecha incorrecto";
                }
                
                
            } else { // email
                     
                // Valida email
                if (filter_var($cadena, FILTER_VALIDATE_EMAIL)) {
                    $this->_errores[$campo] = '';
                } else {
                    $this->_errores[$campo] = $campo . ' no es válido';
                }
            }
        } else {
            $this->_errores[$campo] = 'Introduzca ' . $campo;
        }
    }

    /**
     *
     * @param string $campo            
     *
     * @return valor $_REQUEST|cadena string vacía
     */
    public function valPost($campo, $tipo)
    {
        if (isset($_REQUEST[$campo])) {
            switch ($tipo) {
                case "email":
                    $cadena = filter_var(trim($_REQUEST[$campo]), FILTER_SANITIZE_EMAIL);
                    break;
                default:
                    $cadena = filter_var(trim($_REQUEST[$campo]), FILTER_SANITIZE_STRING);
                    break;
            }
            // Validamos cadena sanitizada
            $this->filterValue($cadena, $tipo, $campo);
            
            return $cadena;
        } else
            return 'No existe el campo ' . $campo;
    }

    /**
     * Función que recibe los campos esperados del formulario que devuelve los datos saneados y errores encontrados
     *
     * @param array $data
     *            campos esperados del formulario
     * @return multitype:string valor
     */
    public function checkForm($data)
    {
        foreach ($data as $key => $value) {
            // saneamos datos
            $dataForm[$key] = $this->valPost($key, $value);
        }
        
        $datos = array(
            'datos' => $dataForm,
            'errores' => $this->_errores
        );
        // var_dump($this->_errores);
        
        return $datos;
    }
}