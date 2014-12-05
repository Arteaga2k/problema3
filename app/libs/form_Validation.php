<?php

/**
 * Clase validation
 * 
 * Comprueba campos esperados del formulario, sanitiza y filtra.
 * 
 * @author Carlos *
 */
class Validation
{

    /**
     *
     * @var unknown
     */
    private $_errores;

    /**
     * Filtra el valor de una cadena recibida y guarda errores
     *
     * @param String $cadena            
     * @param String $tipo            
     * @return mixed|boolean
     */
    public function filterValue($cadena, $tipo, $campo)
    {
        $opcion = array(
            'texto' => '/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/',
            'telefono' => '/^[0-9]{6,20}+$/',
            'consulta' => '/^[0-9]+$/',
            'codpostal' => '/^[0-9]{5}+$/',
            'alfanum' => '#^[a-z0-9\s]+$#i',
            'numerico' => '/^[0-9]+$/'
         
        );
        
        if ($tipo == "date") {
            $fecha = explode("-", $cadena);
            
            if (count($fecha) == 3) {
                
                if (! checkdate($fecha[1], $fecha[2], $fecha[0])) {
                    $this->_errores[$campo] = "La fecha no es válida";
                }
            } else {
                $this->_errores[$campo] = "Formato de fecha incorrecto";
            }
        } else 
            if ($tipo == "email") { // email
                
                if (! filter_var($cadena, FILTER_VALIDATE_EMAIL)) {
                    $this->_errores[$campo] = 'Introduzca o revise el valor';
                }
            } else 
                if ($tipo == 'richtext') {
                    // solo sanitizamos
                } else {
                    if (! filter_var($cadena, FILTER_VALIDATE_REGEXP, array(
                        "options" => array(
                            "regexp" => $opcion[$tipo]
                        )
                    ))) {
                        $this->_errores[$campo] = 'Introduzca o revise el valor';
                    }
                }
    }

    /**
     *
     * @param string $campo            
     *
     * @return valor $_REQUEST|cadena string vacía
     */
    public function valida_Sanitiza($campo, $tipo)
    {
        if (isset($_REQUEST[$campo])) {
            switch ($tipo) {
                case "email":
                    $cadena = filter_var(trim($_REQUEST[$campo]), FILTER_SANITIZE_EMAIL);
                    break;
                case "richtext":
                    $cadena = filter_var(trim($_REQUEST[$campo]), FILTER_SANITIZE_SPECIAL_CHARS);
                    break;
                default:
                    $cadena = filter_var(trim($_REQUEST[$campo]), FILTER_SANITIZE_STRING);
                    break;
            }
            // Validamos cadena sanitizada
            $this->filterValue($cadena, $tipo, $campo);
            return $cadena;
        } else
            return; //'No existe el campo ' . $campo;
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
            $dataForm[$key] = $this->valida_Sanitiza($key, $value);
        }
        
        $datos = array(
            'datos' => $dataForm, // valores sanitizados y validados
            'errores' => $this->_errores
        );
        return $datos;
    }
}