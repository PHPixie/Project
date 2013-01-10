<?php

/**
 * Database related functions. Creates connections,
 * executes queries and returns results. It is also the
 * generic connection class used by drivers.
 * @package Database
 */
abstract class DB {

    /**
     * An associative array of connections to databases
     * @var array   
     * @access private 
     * @static 
     */
	private static $_instances=array();

    /**
     * Executes a prepared statement query
     * 
     * @param string   $query  A prepared statement query
     * @param array     $params Parameters for the query
	 * @return Result_Database
     * @access public    
	 * @see Result_Database
     */
	public abstract function execute($query, $params = array());

    /**
     * Builds a new Query to the database
     * 
     * @param string $type Query type. Available types: select, update, insert, delete, count
	 * @return Result_Database
     * @access public  
	 * @see Query_Database
     */
	public abstract function build_query($type);

    /**
     * Gets the id of the last inserted row.
     * 
	 * @return mixed The id of the last inserted row
     * @access public 
     */
	public abstract function get_insert_id();

    /**
     * Executes a named query where parameters are passed as an associative array
	 * Example:
	 * <code>
	 * $result=$db->namedQuery("SELECT * FROM fairies where name = :name",array('name'=>'Tinkerbell'));
	 * </code>
     * 
     * @param string $query  A named query
     * @param array   $params Associative array of parameters
     * @return Result_Database   Current drivers implementation of Result_Database
     * @access public  
     */
	public function namedQuery($query, $params=array()) {
		$bind = array();
		preg_match_all('#:(\w+)#is', $query, $matches,PREG_SET_ORDER);
		foreach($matches as $match)
			if(isset($params[$match[1]])){
				$query = preg_replace("#{$match[0]}#", '?', $query, 1);
				$bind[] = $params[$match[1]];
			}
		return $this->execute($query,$bind);
	}

    /**
     * Returns an Expression_Database representation of the value.
	 * Values wrapped inside Expression_Database are not escaped in queries
     * 
     * @param mixed $value Value to be wrapped
     * @return Expression_Database  Raw value that will not be escaped during query building
     * @access public  
     * @static 
     */
	public static function expr($value){
		return new Expression_Database($value);
	}
	
    /**
     * Builds a query for specified connection.
     * 
     * @param string $type   Query type. Available types: select,update,insert,delete,count
     * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
     * @return Query_Database  Driver implementation of the Query_Database class.
     * @access public  
     * @static 
     */
	public static function query($type,$config = 'default') {
		return DB::instance($config)->build_query($type);
	}

    /**
     * Gets the id of the last inserted row
     * 
     * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
     * @return mixed Id of the last inserted row
     * @access public 
     * @static 
     */
	public static function insert_id($config = 'default') {
		return DB::instance($config)->get_insert_id();
	}

    /**
     * Gets an instance of a connection to the database
     * 
     * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
     * @return DB  Driver implementation of the DB class.
     * @access public 
     * @static 
     */
	public static function instance($config='default'){
		if (!isset(DB::$_instances[$config])) {
			$driver = Config::get("database.{$config}.driver");
			$driver="DB_{$driver}_Driver";
			DB::$_instances[$config] = new $driver($config);
		}
		return DB::$_instances[$config];
	}

}