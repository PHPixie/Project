<?php

/**
 * XCache cache driver.
 * @package Cache
 */
class Xcache_Cache extends Abstract_Cache {

	protected function _set($key, $value, $lifetime) {
		xcache_set($key, $value, $lifetime);
	}
	
	protected function _get($key) {
		return xcache_get($key);
	}
	
	protected function _delete_all() {
		xcache_clear_cache(XC_TYPE_VAR, -1);
	}
	
	protected function _delete($key) {
		xcache_unset($key);
	}
}