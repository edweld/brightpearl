<?php
/*
 * @author ed
 * @TODO implement standard error controller
 */
class ErrorController extends Zend_Controller_Action
{	
    function cliAction ()
    {
        $this->_helper->viewRenderer->setNoRender (true);

        $error = $this->_getParam ('error_handler');
        if ($error instanceof Exception)
        {
            print $error->getMessage () . "\n";
        }
    }
}
