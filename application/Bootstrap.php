<?php

/**
 * Standard implementation of Bootstrap, I've created a CLI specific router
 * @author ed
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Initialize Autoloader
     * For this simple implementation, we are not implementing any namespacing, 
     * but need to overide this here
     */
    protected function _initAutoload() {
        date_default_timezone_set("Europe/London");
        $resourceLoader = new Zend_Application_Module_Autoloader(array('namespace' => '', 'basePath' => dirname(__FILE__)));
        return $resourceLoader;
    }
    /*
     * Custom router for CLI interface
     * @TODO implement standard router switch implementing php_sapi_name() == 'cli'
     */
    protected function _initRouter ()
    {
        $this->bootstrap ('frontcontroller');
        $front = $this->getResource('frontcontroller');
        $router = new Model_Router();
        $front->setRouter ($router);
        $front->setRequest (new Zend_Controller_Request_Simple());
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
    }
}
