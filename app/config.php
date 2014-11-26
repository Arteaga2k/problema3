<?php


/**
 * Configuración: La URL del proyecto problema1
 */
define('URL', 'http://localhost/problema3/');



/**
 * ConfiguraciÓn: Parámetros de la base de datos
 * Contrase�a, tipo, usuario...etc
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'kenollega');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Configuraci�n : Vistas
 *
 * PATH_VIEWS es la ruta donde se encuentran los archivos con las vistas
 * PATH_VIEW_FILE_TYPE es la extensi�n de las vistas, en este caso usaremos motor plantilla twig.
 */
define('PATH_VIEWS', 'app/views/');
define('PATH_VIEW_FILE_TYPE', '.twig');


/**
 * Configuración: Parámetros de la paginación
 * @var unknown
 */
define('REGS_PAG', 10);
define('RANGE_PAG', 3);

/**
 * Configuration: Cookies
 */
// 1209600 segundos = 2 semanas
define('COOKIE_RUNTIME', 1209600);
// .localhost también vale
define('COOKIE_DOMAIN', '.127.0.0.1');

// the hash cost factor, PHP's internal default is 10. You can leave this line
// commented out until you need another factor then 10.
define("HASH_COST_FACTOR", "10");

