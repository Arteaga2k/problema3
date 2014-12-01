<?php
// Cargamos la configuraci�n de la aplicaci�n
require 'app/config.php';

// Cargamos el n�cleo de lo que va a ser la aplicaci�n
require 'app/libs/application.php';
require 'app/libs/controller.php';
require 'app/kint/Kint.class.php';
require 'app/libs/Session.php';
require 'app/libs/autorizacion.php';
require 'app/libs/configuration.php';

// Cargamos el motor de plantillas Twig
require 'app/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Ejecuta la aplicaci�n
$app = new Application();