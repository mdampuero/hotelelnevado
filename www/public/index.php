<?php
error_reporting(E_ERROR);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)));
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/Inamika'),
    realpath(APPLICATION_PATH . '/../application/models/DbTable'),
    get_include_path(),
)));
define('NAMECMS','Apache CMS');
//PAGINATOR
define('COUNTPERPAGE',15);
define('PAGERANGE', 10);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
define('DS',DIRECTORY_SEPARATOR);

/** Zend_Application */
require_once 'Zend/Application.php';
define('HOST', 'http://'.$_SERVER['HTTP_HOST']);

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();