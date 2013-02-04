<?php

/**
 * APC cache driver.
 * @package Cache
 */
class Apc_Cache extends Abstract_Cache {

	protected function _set($key, $value, $lifetime) {
		apc_store($key, $value, $lifetime);
	}
	
	protected function _get($key) {
		$data = apc_fetch($key, $success);
		if ($success)
			return $data;
	}
	
	protected function _delete_all() {
		apc_clear_cache('user');
	}
	
	protected function _delete($key) {
		apc_delete($key);
	}
}