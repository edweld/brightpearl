<?php

class Model_DateCalcTest extends Model_Test_PHPUnit_ControllerTestCase 
{
    public function setup()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        
        parent::setUp();
        
        $this->appBootstrap();
    }
    
    public function testSetYear()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2016");
        $this->assertEquals("2016", $calc->getYear());
    }
    
    public function testSetMonthStart()
    {
        $calc = new Model_DateCalc();
        $calc->setMonthStart("6");
        $this->assertEquals("6", $calc->getMonth());
    }
    
    public function test_getLastDayOfMonthStandard()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getLastDayOfMonth");
        $res = $method->invoke($calc, 6, 2013);
        $this->assertEquals($res, 30);
    }
    public function test_getLastFridayOfMonth1()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getLastFridayOfMonth");
        $res = $method->invoke($calc, 2, 2013);
        $this->assertEquals(22, $res);
    }
    public function test_getLastFridayOfMonth2()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getLastFridayOfMonth");
        $res = $method->invoke($calc, 11, 2012);
        $this->assertEquals(30, $res);
    }
    public function test_getLastFridayOfMonth3()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getLastFridayOfMonth");
        $res = $method->invoke($calc, 9, 2012);
        $this->assertEquals(28, $res);
    }
    public function test_getLastFridayOfMonth4()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getLastFridayOfMonth");
        $res = $method->invoke($calc, 12, 2011);
        $this->assertEquals(30, $res);
    }
    
    public function test_getDayOfWeekAfter1()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 3, 20, 12, 2012);
        $this->assertEquals(26, $res);
    }
    
    public function test_getDayOfWeekAfter2()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 0, 1, 12, 2012);
        $this->assertEquals(2, $res);
    }
    
    public function test_getDayOfWeekAfter3()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 3, 6, 12, 2012);
        $this->assertEquals(12, $res);
    }
    public function test_getDayOfWeekAfter4()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 3, 22, 2, 2012);
        $this->assertEquals(29, $res);
    }
    public function test_getDayOfWeekAfter5()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 3, 26, 12, 2012);
        //var_dump(date("y-m-d",mktime(0, 0, 1, 12, 33, 2012)));
        $this->assertEquals(33, $res);
    }
    public function test_getDayOfWeekAfter6()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 0, 23, 9, 2012);
        $this->assertEquals(30, $res);
    }
    public function test_getDayOfWeekAfter7()
    {
        $calc = new Model_DateCalc();
        $method = $this->reflectionAdapter($calc, "_getDayOfWeekAfter");
        $res = $method->invoke($calc, 3, 1, 1, 2013);
        $this->assertEquals(2, $res);
    }
    public function test_getbonusDay1()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2012");
        $method = $this->reflectionAdapter($calc, "_getBonusDay");
        $res = $method->invoke($calc,5);
        $result = mktime(0,0,1,5,15,2012);
        $this->assertEquals($result, $res);
    }
    
    public function test_getbonusDay2()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2012");
        $method = $this->reflectionAdapter($calc, "_getBonusDay");
        $res = $method->invoke($calc,12);
        $result = mktime(0,0,1,12,19,2012);
        $this->assertEquals($result, $res);
    }
    
    public function test_getPayDay1()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2013");
        $method = $this->reflectionAdapter($calc, "_getPayDay");
        $res = $method->invoke( $calc, 1);
        $result = mktime(0,0,1,1,31,2013);
        $this->assertEquals($result, $res);
    }
    public function test_getPayDay2()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2013");
        $method = $this->reflectionAdapter($calc, "_getPayDay");
        $res = $method->invoke( $calc, 8);
        $result = mktime(0,0,1,8,30,2013);
        $this->assertEquals($result, $res);
    }
    public function test_getPayDay3()
    {
        $calc = new Model_DateCalc();
        $calc->setYear("2013");
        $method = $this->reflectionAdapter($calc, "_getPayDay");
        $res = $method->invoke( $calc, 6);
        $result = mktime(0,0,1,6,28,2013);
        $this->assertEquals($result, $res);
    }
    public function testGeneratePayData()
    {
        $calc = new Model_DateCalc();
        $calc->setYear(2012);
        $calc->setMonthStart(9);
        $res = $calc->generatePayData();
        $result = array(
            'Sep'=> array(
                'bonus'=>'2012-09-19',
                'pay'=>'2012-09-28'
            ),
            'Oct'=> array(
                'bonus'=>'2012-10-15',
                'pay'=>'2012-10-31'
            ),
            'Nov'=> array(
                'bonus'=>'2012-11-15',
                'pay'=>'2012-11-30'
            ),
            'Dec'=> array(
                'bonus'=>'2012-12-19',
                'pay'=>'2012-12-31'
            )
        );
        $this->assertEquals($res, $result);
    }
}