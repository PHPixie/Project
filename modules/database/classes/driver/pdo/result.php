<?php

/**
 * Database result implementation for PDO
 * @package Database
 */
class Result_PDO_Driver extends Result_Database {

    /**
     * Initializes new result object
     * 
     * @param PDOStatement $stmt PDO Statement
     * @return void    
     * @access public  
	 * @link http://php.net/manual/en/class.pdostatement.php
     */
	public function __construct($stmt) {
		$this->_result = $stmt;
		$this->_row=$this->_result->fetchObject();
	}

    /**
     * Throws exception if rewind is attempted.
     * 
     * @return void      
     * @access public    
     * @throws Exception If rewind is attempted
     */
    public function rewind() {
		if($this->_position!=0)
			throw new Exception('PDO statement cannot be rewound for unbuffered queries');
    }
		
    /**
     * Iterates to the next row in the result set
     * 
     * @return void   
     * @access public 
     */
    public function next() {

        $this->_position++;
		$this->_row=$this->_result->fetchObject();
		if ($this->_row == false)
				$this->_result->closeCursor();
    }
    	
}