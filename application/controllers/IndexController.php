<?php

/**
 * 
 *
 * @author ed
 */
class IndexController extends Zend_Controller_Action {
   
    public function _init()
    {
        $this->_helper->layout()->disableLayout();
    }
    
    public function indexAction(){
        try{
        $generated = false;
        $errors = array();
        $year = $this->getRequest()->getParam("y",false);
        $month = $this->getRequest()->getParam("m",false);
        $calc = new Model_DateCalc();
        if(false!==$year)
        {
            $validator = new Zend_Validate_Date(array('format' => 'yyyy'));
            if($validator->isValid($year))
            {
                 $calc->setYear($year);
            }
            else 
            {
                $errors['year'] = array("Year"=> "must be in the format YYYY");
            }
        }
        if(false!==$month)
        {
            $validator = new Zend_Validate();
            $validator->addValidator( new Zend_Validate_Int());
            $validator->addValidator( new Zend_Validate_Between(array("min"=>1, "max"=>12)));
            if($validator->isValid($month))
            {
                $calc->setMonthStart($month);
            }
            else
            {
                $errors['month'] = array("Month"=>"Must be an integer between 0 and 12");
            }
        
        }
        $data = $calc->generatePayData();
        if(!is_array($data))
        {
            $errors['generating']='could not generate data from parameters';
        }
        $csv = new Model_CsvFromArray();
        $date = Date("Ym",mktime(0,0,1,$calc->getMonth(),1,$calc->getYear()) );
        $outputPath = APPLICATION_PATH."/../output/".'Pay-Dates'.$date.".csv";
        $output = $csv->generateCsv($data, $outputPath);
        if($csv->hasErrors())
        {
            $errors['csv data']=$csv->getErrors();
        }
        $this->view->file = realpath($outputPath);
        $this->view->generated = true;
        $this->view->errors = $errors;
        $this->view->year = $calc->getYear();
        $this->view->month = date("F", mktime(0,0,1,$calc->getMonth(),1,$calc->getYear()))." (".$calc->getMonth().")";
        }catch(Exception $e){ var_dump($e->getMessage());}
    }
}
