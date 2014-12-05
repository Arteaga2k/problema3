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
        // estamos logueado?
        Autorizacion::checkLogin();
        
        $envios_model = $this->loadModel('EnviosModel');
        $zona_model = $this->loadModel('ZonaModel');
        
        $piechart = $envios_model->pieChart();              
        
        $envios = $envios_model->getEnvios($_SESSION['usuario_zona']);
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        $zonas = $zona_model->getZonas();
        
        $this->render('home/index', array(
            'tabla' => 'dashboard',
            'cabecera' => 'Dashboard',
            'usuario' => Session::get('usuario_nombre'),
            'hora' => Session::get('usuario_hora_inicio'),
            'envios' => $envios,
            'zona_usuario' => $zona['nombrezona'],
            'zonas' => $zonas,
            'pieChart' => $piechart,
            'avatar' => session::get('AVATAR'),
            'tema' => Session::get('TEMA')
        ));
    }
    
    

    
}