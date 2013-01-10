<?php

/**
 * Allows iterating over ORM objects inside loops lie 'foreach',
 * while preserving performance by working with only a single row
 * at a time. It wraps conveniently wraps around Database_Result class
 * returning ORM object instead of just data object.
 *
 * @see Database_Result
 * @package ORM
 */
class ORMResult implements Iterator {

    /**
     * Name of the model that the rows belong to
     * @var string 
     * @access private 
     */
	private $_model;

    /**
     * Database result
     * @var Result_Database  
     * @access private 
     */
	private $_dbresult;

    /**
     * Initialized an ORMResult with which model to use and which result to
	 * iterate over
     * 
     * @param string $model  Model name
     * @param Result_Database $dbresult Database result
     * @return void    
     * @access public  
     */
	public function __construct($model,$dbresult){
		$this->_model=$model;
		$this->_dbresult = $dbresult;
	}

    /**
     * Rewinds database cursor to the first row
     * 
     * @return void   
     * @access public 
     */
    function rewind() {
		$this->_dbresult->rewind();
    }

    /**
     * Gets an ORM Model of the current row
     * 
     * @return ORM Model of the current row of the result set
     * @access public 
     */
    function current() {
        $model = new $this->_model;
		if (!$this->_dbresult->valid())
			return $model;
		$model->values((array)$this->_dbresult->current(),true);
		return $model;
    }

    /**
     * Gets current rows' index number
     * 
     * @return int Row number
     * @access public 
     */
    function key() {
        return $this->_dbresult->key();
    }

    /**
     * Iterates to the next row in the result
     * 
     * @return void
     * @access public 
     */
    function next() {
        $this->_dbresult->next();
    }

    /**
     * Checks if current row is valid. 
     * 
     * @return bool returns false if we reach the end of the result set.
     * @access public 
     */
    function valid() {
		return $this->_dbresult->valid();
    }

    /**
     * Returns an array of all rows as ORM objects if $rows is False,
	 * or just an array of result rows with each row being a standard object,
	 * this can be useful for functions like json_encode.
     * 
     * @param boolean $rows Whether to return just rows and not ORM objects
     * @return array   Array of ORM objects or standard objects representing rows
     * @access public  
     */
	public function as_array($rows = false) {
		if ($rows)
			return $this->_dbresult->as_array();
		$arr = array();
		foreach($this as $row)
			$arr[] = $row;
		return $arr;
	}
	
}