<?php
/**
 * Simple CLI router, I could have implemented this in the bootstrap,
 * but this style of implementation allows for a standard router to be implemented
 * in future if you wished to extend this into a web based application
 * @author ed
 */
class Model_Router extends Zend_Controller_Router_Abstract 
{    
    public function route (Zend_Controller_Request_Abstract $dispatcher)
    {
        try{
            $getopt = new Zend_Console_Getopt (array(
                "y|year-i"         =>"generate paydays for the year",
                "m|month-i"     =>"generate paydays from month 0-12"    
            ));
            $getopt->parse();
        } catch (Exception $e){
            $message = $e->getMessage();
            $dispatcher->setControllerName ('error');
            $dispatcher->setActionName ('cli');
            $dispatcher->setParam("error_handler", $e);
            return $dispatcher;
        }
        /*
         * we are currently only expecting "index"
         */
        $arguments = $getopt->getRemainingArgs ();
        
        if ($arguments)
        {
            $command = array_shift ($arguments);
            if (! preg_match ('~\W~', $command))
            {
                $dispatcher->setActionName ($command);
            }
            
        }  else {
            $dispatcher->setControllerName ('index');
        }
        $request = array();
        $options = $getopt->getOptions();
        if($options)
        {
            foreach( $options as $option)
            {
                $request[$option] = $getopt->$option;
            }
        }
        $dispatcher->setParams($request);
        return $dispatcher;
        
    }
/*
 * the assemble method must be defined as this class 
 * must implement Zend_Controller_Router_Interface
 * but it is not used in this implementation
 */
    public function assemble ($userParams, $name = null, $reset = false, $encode = true)
    {
        echo "Not implemented\n", exit;
    }   
}