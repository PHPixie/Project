<?php
class File_Cache extends Abstract_Cache {

	protected $_cache_dir;
	
	public function __construct($config) {
		parent::__construct($config);
		$this->_cache_dir = Config::get("cache.{$config}.cache_dir", ROOTDIR.'/modules/cache/cache/');
	}

	protected function _set($key, $value, $lifetime) {
		if (!is_dir($key['dir']))
			mkdir($key['dir'], true);
		$expires=time()+$lifetime;
		file_put_contents($key['dir'].$key['file'],$expires."\n".serialize($value));
	}
	
	protected function _get($key) {
		$file = $key['dir'].$key['file'];
		
		if (file_exists($file) && $this->check_file($file)) {
			$data = file_get_contents($file);
			$data = substr($data, strpos($data, "\n")+1);
			return unserialize($data);
		}
		
		if (is_dir($key['dir']))
			$this->check_dir($key['dir']);
	}
	
	protected function _delete_all() {
		$dirs = array_diff(scandir($this->_cache_dir), array('.', '..')); 
		foreach($dirs as $dir) {
			$dir=$this->_cache_dir.'/'.$dir;
			$files=array_diff(scandir($dir), array('.', '..')); 
			foreach($files as $file)
				unlink($dir.'/'.$file);
			rmdir($dir);
		}
	}
	
	protected function _delete($key) {
		if (file_exists($key['dir'].$key['file']))
			unlink($key['dir'].$key['file']);
		if(is_dir($key['dir']))
			$this->check_dir($key['dir']);
	}
	
	public function garbage_collect() {
		$dirs = array_diff(scandir($this->_cache_dir), array('.', '..')); 
		foreach($dirs as $dir) {
			$dir=$this->_cache_dir.'/'.$dir;
			$files=array_diff(scandir($dir), array('.', '..')); 
			foreach($files as $file) 
				$this->check_file($dir.'/'.$file);
			$this->check_dir($dir);
		}
	}
	
	protected function check_dir($dir) {
		if (count(scandir($dir)) == 2)
			rmdir($dir);
	}
	
	protected function check_file($file) {
		$fp = fopen($file, 'r');
		$expires = fgets($fp);
		fclose($fp);
		
		if ($expires < time()){
			unlink($file);
			return false;
		}
		
		return true;
	}
	
	protected function sanitize($key) {
		$key = md5($key);
		return array(
			'dir' => $this->_cache_dir.'/'.substr($key, 0, 2).'/',
			'file' => $key
		);
	}
}