<?php
/**
 * Defines APC implementation of nosql operations.
 *
 * DOCS: http://php.net/manual/en/book.apc.php
 */
class APCDriver implements NoSQLDBOperations {
	public function add($key, $value, $expiration=0) {
		apc_add($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		apc_store($key, $value, $expiration);
	}

	public function get($key) {
		return apc_fetch($key);
	}

	public function delete($key) {
		apc_delete($key);
	}
	
	public function contains($key) {
		return apc_exists($key);
	}

	public function increment($key, $offset = 1) {
		return apc_inc($key, $offset);
	}

	public function decrement($key, $offset = 1) {
		return apc_dec($key, $offset);
	}
}