<?php

/**
 * Mysqli Database Implementation
 * @package Database
 */
class DB_Mysql_Driver extends DB{

    /**
     * Mysqli database connection object
     * @var mysqli 
     * @access public 
	 * @link http://php.net/manual/en/class.mysqli.php
     */
	public $conn;
	
	/**
     * Type of the database, mysql.
     * @var string 
     * @access public 
     */
	public $db_type='mysql';
	
    /**
     * Initializes database connection
     * 
     * @param string $config Name of the connection to initialize
     * @return void    
     * @access public  
     */
	public function __construct($config) {
		$this->conn = mysqli_connect(
			Config::get("database.{$config}.host",'localhost'),
			Config::get("database.{$config}.user",''),
			Config::get("database.{$config}.password",''),
			Config::get("database.{$config}.db")
		);
	}

    /**
     * Builds a new Query implementation
     * 
     * @param string $type Query type. Available types: select,update,insert,delete,count
     * @return Query_Mysql_Driver  Returns a Mysqli implementation of a Query.
     * @access public  
	 * @see Query_Database
     */
	public function build_query($type) {
		return new Query_Mysql_Driver($this,$type);
	}

    /**
     * Gets the id of the last inserted row.
     * 
     * @return mixed Row id
     * @access public 
     */
	public function get_insert_id() {
		return $this->conn->insert_id;
	}

    /**
     * Executes a prepared statement query
     * 
     * @param string   $query  A prepared statement query
     * @param array     $params Parameters for the query
     * @return Result_Mysql_Driver    Mysqli implementation of a database result
     * @access public    
     * @throws Exception If the query resulted in an error
	 * @see Database_Result
     */
	public function execute($query, $params = array()) {
		$cursor = $this->conn->prepare($query);
		if (!$cursor) 
			throw new Exception("Database error: {$this->conn->error} \n in query:\n{$query}");
		$types = '';
		$bind = array();
		$refs = array();
		if(!empty($params)){
			foreach($params as $key=>$param) {
				$refs[$key]=is_array($param)?$param[0]:$param;
				$bind[]=&$refs[$key];
				$types.=is_array($param)?$param[1]:'s';
			}
			array_unshift($bind, $types);
			
			call_user_func_array(array($cursor, 'bind_param'), $bind);
		}
		$cursor->execute();
		$res = $cursor->get_result();
		if (is_object($res)){
			$res=new Result_Mysql_Driver($res);
		}
		return $res;
	}
}