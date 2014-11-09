<?php
// Cargamos la configuraci�n de la aplicaci�n
require 'app/config.php';

// Cargamos el n�cleo de lo que va a ser la aplicaci�n
require 'app/core/application.php';
require 'app/core/controller.php';
require 'app/core/model.php';

// Cargamos el motor de plantillas Twig
require 'app/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Ejecuta la aplicaci�n
$app = new Application();