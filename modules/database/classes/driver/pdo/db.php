<?php

/**
 * PDO Database implementation.
 * @package Database
 */
class DB_PDO_Driver extends DB{

    /**
     * Connection object
     * @var PDO 
     * @access public 
	 * @link http://php.net/manual/en/class.pdo.php
     */
	public $conn;
	
	/**
     * Type of the database, e.g. mysql, pgsql etc.
     * @var string 
     * @access public 
     */
	public $db_type;
	
    /**
     * Initializes database connection
     * 
     * @param string $config Name of the connection to initialize
     * @return void    
     * @access public  
     */
	public function __construct($config) {
		$this->conn = new PDO(
			Config::get("database.{$config}.connection"),
			Config::get("database.{$config}.user",''),
			Config::get("database.{$config}.password",'')
		);
		$this->db_type=strtolower(str_replace('PDO_', '', $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME)));
	}

    /**
     * Builds a new Query implementation
     * 
     * @param string $type Query type. Available types: select,update,insert,delete,count
     * @return Query_PDO_Driver  Returns a PDO implementation of a Query.
     * @access public  
	 * @see Query_Database
     */
	public function build_query($type) {
		return new Query_PDO_Driver($this,$type);
	}

    /**
     * Gets the id of the last inserted row.
     * 
     * @return mixed Row id
     * @access public 
     */
	public function get_insert_id() {
		if ($this->db_type == 'pgsql')
			return $this->execute('SELECT lastval() as id')->current()->id;
		return $this->conn->lastInsertId();
	}

    /**
     * Executes a prepared statement query
     * 
     * @param string   $query  A prepared statement query
     * @param array     $params Parameters for the query
     * @return Result_PDO_Driver    PDO implementation of a database result
     * @access public    
     * @throws Exception If the query resulted in an error
	 * @see Database_Result
     */
	public function execute($query, $params = array()) {
		$cursor = $this->conn->prepare($query);
		if (!$cursor->execute($params)) {
			$error = $cursor->errorInfo();
			throw new Exception("Database error:\n".$error[2]." \n in query:\n{$query}");
		}
		return new Result_PDO_Driver($cursor);
	}
}