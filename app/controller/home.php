<?php

/**
 * CLASE home
 * 
 * Controlador página principal (dashboard)
 * 
 * @author Carlos
 */
class Home extends Controller
{

    /**
     * PÁGINA: dashboard
     *
     * http://problema1/index
     */
    public function index()
    {
    	Autorizacion::checkLogin();
        
        $envios_model = $this->loadModel('EnviosModel');
        $envios = $envios_model->getEnvios();
        
        var_dump($envios);
        
        $this->render('home/index', array(
            'title' => 'Dashboard',
            'envios' => $envios
        ));
    }
}