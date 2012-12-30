<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CsvFromArrayTest
 *
 * @author ed
 */
class CsvFromArrayTest extends Model_Test_PHPUnit_ControllerTestCase {
    public function setup()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        
        parent::setUp();
        
        $this->appBootstrap();
    }
    
    public function test_setDataException()
    {
        $csv = new Model_CsvFromArray();
        $this->setExpectedException("Exception");
        $method = $this->reflectionAdapter($csv, "_setData");
        $res = $method->invoke($csv,"string");
    }
    
    public function test_writeFile()
    {
        $csv = new Model_CsvFromArray();
        $outputPath = APPLICATION_PATH."/../output/".'Pay-Datestest.csv';
        $data = "testdata";
        $method = $this->reflectionAdapter($csv, "_writeFile");
        $res = $method->invoke($csv, $data, $outputPath);
        $this->assertEquals(file_exists($outputPath), true);
        }
    
    public function test_generate()
    {
        $data = array(
            array("integer"=>1, "boolean"=>true),
            array("string"=>"string", "float"=>(float) 22.33)
            );
        $csv = new Model_CsvFromArray();
        $method = $this->reflectionAdapter($csv, "_setData");
        $method->invoke($csv,$data);
        $method = $this->reflectionAdapter($csv, "_generate");
        $res = $method->invoke($csv);
        $this->assertEquals($res, "1,\"Y\"\r\n\"string\",22.33\r\n");
    }
}

?>
