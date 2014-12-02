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
        $zona_model = $this->loadModel('ZonaModel');
        
        $envios = $envios_model->getEnvios($_SESSION['usuario_zona']);
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        $zonas = $zona_model->getZonas();
        
        $this->render('home/index', array(
            'tabla' => 'dashboard',
            'cabecera' => 'Dashboard',
            'usuario' => $_SESSION['usuario_nombre'],
            'envios' => $envios,
            'zona_usuario' => $zona['nombrezona'],
            'zonas' => $zonas
        ));
    }

    
}