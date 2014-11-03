<?php

/**
 * 
 * @author Carlos
 *
 */
class Home extends Controller
{

    public function index()
    {
        
        // load a model, perform an action, pass the returned data to a variable
        $envios_model = $this->loadModel('EnviosModel');
        $envios = $envios_model->getAllEnvios();
        
        $this->render('home/index', array(
            'envios' => $envios
        ));
    }
}