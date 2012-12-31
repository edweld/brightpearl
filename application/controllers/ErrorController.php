<?php
/*
 * @author ed
 * @TODO implement standard error controller handling
 */
class ErrorController extends Zend_Controller_Action
{	
    function cliAction ()
    {
        $this->_helper->viewRenderer->setNoRender (true);
        $error = $this->_getParam ('error_handler');
        if ($error instanceof Exception)
        {
            print $error->getMessage() . PHP_EOL;
        }
    }
    
    function errorAction()
    {
        $this->_helper->viewRenderer->setNoRender (true);
        $error = $this->_getParam ('error_handler');
        switch ($error->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                print "404 Error: Controller or action not found".PHP_EOL;
                break;
            default:
                // application error
                print "500 Error: Application error".PHP_EOL;
                break;
        }
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            print $error->exception->getMessage().PHP_EOL;
        }
    }
}
