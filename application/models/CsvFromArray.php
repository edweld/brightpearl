<?php
/**
 * Simple model to generate a CSV from an array
 * @author ed
 */
class Model_CsvFromArray {
    
    /*
     * @access protected
     * @var string
     */
    protected $_delimiter = ',';
    /*
     * @access protected
     * @var string
     */
    protected $_encapsulator = '"';
    /*
     * @access protected
     * @var string
     */
    protected $_newLine = "\r\n";
    /*
     * @access protected
     * @var array
     */
    protected $_data = array();
    /*
     * Internal variable used to store any data errors
     * @access protected
     * @var array
     */
    protected $_errors = array();

    /*
     * Stores an array of data internally
     * @param array $data expects an 2 dimensional array of data to store
     * @return null
     * @access protected
     */
    public function _setData($data)
    {
        if(!is_array($data))
        {
            throw new Exception("_setData expects an array of data to write");
        }
        $this->_data = $data;
    }
    
    /*
     * @param array $data a 2 dimensional array of data to write to a file
     * @param string $fileName a full path filename to write the data to
     * @return null
     * @access public
     */
    public function generateCsv($data, $fileName )
    {
        $this->_setData($data);
        $raw = $this->_generate();
        $success = $this->_writeFile($raw, $fileName);
    }
    /*
     * generates a string of data prepared for writing to CSV
     * @return string csv formatted string, presumes that self::_setData(array) has already been implemented
     * @access protected
     */
    protected function _generate()
    {
        if( 0 === count($this->_data))
        {
            throw new Exception("no CSV data set");
        }
        $out = '';
        $r = 0;
        foreach($this->_data as $row)
        {
            $r++;
            $i=0;
            $delimeter='';
            foreach($row as $data)
            {
                $out.=$delimeter;
                $i++;
                switch(gettype($data))
                {
                    case "boolean":
                      $out .= $this->_encapsulator. ($data ===true ? "Y":"N") .$this->_encapsulator;  
                    break;
                    case "integer":
                        $out.= $data;
                    break;
                    case "string":
                        $out.=$this->_encapsulator. $data . $this->_encapsulator;
                    break;
                    case "double":
                         $out.=$data;
                    break;
                    default:
                        $out .=$this->_encapsulator."ERROR".gettype($data).$this->_encapsulator;
                        $this->_errors[] = "row:".$r.", index:".$i.", erronous data type".gettype($data);
                    break;
                }
                $delimeter = $this->_delimiter;
            }
            $out.=$this->_newLine;
        }
    return $out;
    } 
    /*
     * returns a boolean value based on any whether there was any erronousdata
     * @return boolean whether the where any parsing errors
     * @internal other errors should throw exceptions
     * @access public
     */
    public function hasErrors()
    {
        return count($this->_errors)> 0 ? true : false;
    }
    /*
     * returns an array of parsing errors
     * @return array an array of errors encountered when parsing the data
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    /*
     * @param string $encapsulator set the csv encapsulator (default is '"')
     * @access public
     */
    public function setEncapsulator($encapsulator)
    {
        $this->_encapsulator = $encapsulator;
    }
    /*
     * @param string $delimeter set the field delimeter (default is ',')
     * @access public
     */
    public function setDelimeter($delimeter)
    {
        $this->_delimiter = $delimeter;
    }
    /*
     * @param string $newLine set the new line character (default is '\r\n')
     * @access public
     */
    public function setNewLine($newLine)       
    {
        $this->_newLine = $newLine;
    }
    /*
     * Method to write data to file
     * @param string $raw raw csv formatted data to write
     * @param string $fileName the path of the file to write
     * @access protected
     */
    protected function _writeFile( $raw, $fileName)
    {
        $fh = fopen($fileName, "w+");
        if(false===$fh)
        {
            throw new Exception("Could not open file for writing");
        }
        fwrite($fh, $raw);
        fclose($fh);
        //assumes if no exceptions where thrown, procedure was successful
        return true;
    }
}
