<?php

/**
 * Database cache driver.
 * @package Cache
 */
class Database_Cache extends Abstract_Cache {
	
	protected $_db;
	
	public function __construct($config) {
		parent::__construct($config);
		$this->_db = DB::instance(Config::get("cache.{$config}.connection",'default'));
		$this->_db->execute("CREATE TABLE IF NOT EXISTS cache (
			name VARCHAR(255) NOT NULL PRIMARY KEY, 
			value TEXT, 
			expires INT
		)");
		
	}
	protected function _set($key, $value, $lifetime) {
		$this->_db->execute("REPLACE INTO cache(name,value,expires) values (?, ?, ?)", array(
			$key,serialize($value),time()+$lifetime
		));
	}
	
	protected function _get($key) {
		$this->garbage_collect();
		$data = $this->_db->execute("SELECT value FROM cache where name = ?", array($key))->get('value');
		if ($data !== null)
			return unserialize($data);
	}
	
	protected function _delete_all() {
		$this->_db->execute("DELETE FROM cache");
	}
	
	protected function _delete($key) {
		$this->_db->execute("DELETE FROM cache WHERE name = ?",array($key));
	}
	
	public function garbage_collect() {
		$this->_db->execute("DELETE FROM cache WHERE expires < ?",array(time()));
	}
}