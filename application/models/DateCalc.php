<?php   
/**
 * Simple Date calculation model
 * to calculate pay data based on certain parameters
 * 
 * @author ed
 */
class Model_DateCalc {
    
    /*
     * @access protected
     * @var int
     */
    protected $_year;
    
    /*
     * @access protected
     * @var int
     */
    protected $_month;
    
    /*
     * @access protected
     * @var boolean
     * @deprec
     */
    protected $_checkPastDate = true;
    
    public function __construct()
    {
        $this->_year = date("Y", strtotime("now"));
        $this->_month = date("m", strtotime("now"));
    }
    
    /*
     * Set the required year for generating pay data
     * @param int $year format YYYY 
     * @return null
     * @access public
     */
    
    public function setYear($year)
    {
        $this->_year = $year;
    }
    /*
     * Set the required month for generating pay data
     * @param int $month an integer between 1 and 12 
     * @return null
     * @access public
     */
    
    public function setMonthStart($month)
    {
        $this->_month = $month;
    }
    /*
     * Generates pay bonus and pay dates implementing the parameters set 
     * self::setMonthStart(int) and self::setYear(int)
     * the default current year and month are set in the constructor
     * @return array an array of all pay dates for the remaining months of the year
     * @access public
     */    
    public function generatePayData()
    {
        $return = array();
        for($m=12; $m>=$this->_month; $m--)
        {
            $return[date("M", mktime(0, 0, 1, $m, 1, $this->_year))] 
                = array(
                    'month'=>date("F Y", mktime(0, 0, 1, $m, 1, $this->_year)),
                    'bonus'=> date("Y-m-d",$this->_getBonusDay($m)),
                    'pay'=>date("Y-m-d",$this->_getPayDay($m))
                );
        }    
        return array_reverse($return);
    }
       
    /*
     * Returns the last weekday in a given month, or, alternatively the last Friday of that month
     * We presume that the year has been set using method $_self::setYear(YYYY)
     * otherwise the default current year is set in the constructor
     * @param int $month the month as an integer ranging 1-12
     * @return timestamp mktime(0,0,1, $month, lastweekday/friday, $_year)
     * @access protected
     */
    protected function _getPayDay($month)
    {
        $timestamp = mktime(0, 0, 1, $month, $this->_getlastDayOfMonth($month, $this->_year), $this->_year);
        
        if($this->_isWeekend($timestamp))
        {
            $timestamp = mktime(0, 0, 1, $month, $this->_getLastFridayOfMonth($month, $this->_year), $this->_year);
        }
        return $timestamp;
    }
    
    /*
     * Returns the given a weekday in the month after $day, or, the following wednesday
     * We presume that the year has been set using method $_self::setYear(YYYY)
     * otherwise the default current year is set in the constructor
     * @param int $month the month of the year (1-12)
     * @param int $day
     * @return timestamp mktime(0,0,1,$month, $bonusday, $_year) 
     * @access protected
     */
    
    protected function _getBonusDay($month, $day=15)
    {
        $timestamp = mktime(0, 0, 1, $month, $day,  $this->_year);
        if($this->_isWeekend($timestamp))
        {
            $dom = $this->_getDayOfWeekAfter( 3, 15, $month, $this->_year);
            $timestamp = mktime(0, 0, 1, $month, $dom, $this->_year);
        }
        return $timestamp;
    }
    /*
     * Returns the last Friday of the month 
     * @param $month the month of the year to find the last friday
     * @param $year the year within which the last friday is required
     * @return int the last friday of the month (1-31)
     * @access protected
     */
    protected function _getLastFridayOfMonth($month, $year)
    {
        $monthDays = $this->_getlastDayOfMonth($month, $year); 
        $lastDayOfMonth = date('w', mktime(0, 0, 1, $month, $monthDays, $year)); 
            if($lastDayOfMonth < 5 )
            {
                $dayOfMonth = ($monthDays-($lastDayOfMonth+2));
            }
            elseif(5==$lastDayOfMonth)
            {
                $dayOfMonth = $monthDays;
            }
            else
            {
                $dayOfMonth = ($monthDays-1);
            }
        return $dayOfMonth;
    }
    /*
     * Simple test to see if a timestamp is a weekend
     * @param timestamp $date any timestamp of a date
     * @return boolean whether or not the given date is a weekend
     * @access protected
     */
    protected function _isWeekend($date)
    {
        $weekDay = date('w', $date);
        return ($weekDay == 0 || $weekDay == 6);
    }
    
    /*
     * Returns the last day of a given month and year
     * <p>rather than presume PHP has been compiled with the GREGORIAN calendar functions, 
     * we are forcing the assumtion that this is the case mathematically
     * the alternative is to use:
     * cal_days_in_month(CAL_GREGORIAN, $month, $year);</p>
     * 
     * @param $month int the month (range 1-12)
     * @param $year int the year in the format YYYY  
     * @return int the last day of the month 
     * @access protected
     */
    protected function _getlastDayOfMonth($month, $year) {
        switch ($month) {
            case 2:
                # if year is divisible by 4 and not divisible by 100
                if (($year % 4 == 0) && ($year % 100) > 0)
                    return 29;
                # or if year is divisible by 400
                if ($year % 400 == 0)
                    return 29;
                return 28;
            case 4:
            case 6:
            case 9:
            case 11:
                return 30; 
            default:
                return 31;
        }
    }
    
    /*
     * Returns the specified day of the week after a given day in a month and year
     * @param int $dayOfWeek the day of the week date("w") 0=sunday/6=saturday
     * @param int $afterDay the day in the month after which the result is required 1-31
     * @param int $month the month of the year required (as they all differ)
     * @param int $year the year, these differ in leap years
     * @return int day of the week $dayOfWeek after $afterday
     * @internal this method will is intended to be implemented with mktime() and 
     * will therefore return a month day greater than the number of days in a month
     * @access protected
     */
    protected function _getDayOfWeekAfter($dayOfWeek, $afterDay, $month, $year)
    {
        $add = 7; 
        $firstDayOfMonth = date("w", mktime(0,0,1, $month, 1, $year));
        $dayOfMonth = 1 + ($dayOfWeek - $firstDayOfMonth);
        //if our start day is less than 1st it needs to be the week after
        if($dayOfMonth < 1)
        {
            $dayOfMonth += $add;
        }
        if($dayOfMonth <= $afterDay)
        {
            $i=0;
            while($dayOfMonth <= $afterDay){
                $i++;
                $dayOfMonth +=($add);
            }
        }
        return $dayOfMonth;
    }
    
    /*
     * Returns the protected var $_year
     * @return int year in the format YYYY
     * @access public
     */
    public function getYear()
    {
        return $this->_year;
    }
    /* Returns the protected var $_month
     * @return int month in the format 1=jan 12=dec
     * @access public
     */
    public function getMonth()
    {
        return $this->_month;
    }
}