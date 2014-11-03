<?php
// Cargamos la configuración de la aplicación
require 'app/config.php';

// Cargamos el núcleo de lo que va a ser la aplicación
require 'app/libs/application.php';
require 'app/libs/controller.php';
require 'app/libs/model.php';

// Cargamos el motor de plantillas Twig
require 'app/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Ejecuta la aplicación
$app = new Application();