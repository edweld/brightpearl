<?php 
/**
 * Overloading 
 * 
 */
class Model_Test_PHPUnit_ControllerTestCase  extends Zend_Test_PHPUnit_ControllerTestCase {
	
     public function appBootstrap()
    {
        $this->_application = new Zend_Application(APPLICATION_ENV,
              APPLICATION_PATH . '/configs/application.ini'
        );
        $this->_application->bootstrap();
        /**
         * Fix for ZF-8193
         * http://framework.zend.com/issues/browse/ZF-8193
         * Zend_Controller_Action->getInvokeArg('bootstrap') doesn't work
         * under the unit testing environment.
         */
        $front = Zend_Controller_Front::getInstance();
        if($front->getParam('bootstrap') === null) {
            $front->setParam('bootstrap', $this->_application->getBootstrap());
        }
    }
    /*
     * Implements the php reflection API returning an executable method
     * @param object $obj an instantiated object
     * @param string $name a method name belonging to the object
     * @return object $method ReflectionMethod object
     * @see http://php.net/manual/en/class.reflectionmethod.php
     */
    public function reflectionAdapter($obj, $name)
    {
        if(version_compare(phpversion(), "5.3.4")<0 )
        {
            throw new Exception("PHP reflection adapter requires version 5.3.4 or greater");
        }
        $class = new ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
   }
}