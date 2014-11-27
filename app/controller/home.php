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
        $envios = $envios_model->getAllEnvios();
        
        $this->render('home/index', array(
            'title' => 'Dashboard',
            'envios' => $envios
        ));
    }
}