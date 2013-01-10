<?php

/**
 * Mysqli implementation of the database Query
 * @package Database
 */
class Query_Mysql_Driver extends Query_PDO_Driver {

    /**
     * Creates a new query object, sets mysql specific parameters to get correct queries
     * 
     * @param DB $db   Database connection
     * @param string $type Query type. Available types: select, update, insert, delete, count
     * @return void    
     * @access public  
	 * @see Query_PDO_Driver::__construct()
     */
	public function __construct($db, $type) {
		Query_Database::__construct($db, $type);
		$this->_db_type = 'mysql';
	}
	
}