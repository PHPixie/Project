<?php

/**
 * Allows to access database results in a unified way and
 * provides iterator support, so it can be used inside loops like 'foreach'
 * @package Database
 */
abstract class Result_Database implements Iterator {

    /**
     * Current row number
     * @var integer   
     * @access protected 
     */
    protected $_position = 0;

    /**
     * Database result object
     * @var mixed   
     * @access protected 
     */
	protected $_result;

    /**
     * Current row
     * @var object   
     * @access protected 
     */
    protected $_row;
	
	
    /**
     * Returns current row
     * 
     * @return object Current row in result set
     * @access public  
     */
    public function current() {
        return $this->_row;
    }

    /**
     * Gets the number of the current row
     * 
     * @return integer Row number
     * @access public  
     */
    public function key() {
        return $this->_position;
    }

    /**
     * Check if current row exists.
     * 
     * @return bool True if row exists
     * @access public  
     */
    public function valid() {
		return $this->_row!=null;
    }

    /**
     * Returns all rows as array
     * 
     * @return array  Array of rows
     * @access public 
     */
	public function as_array() {
		$arr = array();
		foreach($this as $row)
			$arr[] = $row;
		return $arr;
	}
	
}