<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('GMT');
define('APPLICATION_ENV', 'testing');
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));
define('APPLICATION_CONFIG', APPLICATION_PATH . '/configs/application.ini');
define('APPLICATION_LANGUAGE', 'en');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library/'),
    realpath(APPLICATION_PATH . '/tests/unit'),
    realpath(APPLICATION_PATH),
    get_include_path()
)));


require_once 'Zend/Application/Module/Autoloader.php';
$loader = new Zend_Application_Module_Autoloader(array(
    'namespace' => '',
    'basePath'  => APPLICATION_PATH,
));

$writer = new Zend_Log_Writer_Stream('php://output');
$log = new Zend_Log($writer);
Zend_Registry::set('log',$log);
