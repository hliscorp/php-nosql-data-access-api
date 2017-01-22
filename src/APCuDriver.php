<?php
/**
 * Defines APCu implementation of nosql operations.
*
* DOCS: http://php.net/manual/en/book.apcu.php
*/
class APCuDriver implements NoSQLConnection {
	public function add($key, $value, $expiration=0) {
		apcu_add($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		apcu_store($key, $value, $expiration);
	}

	public function get($key) {
		return apcu_fetch($key);
	}

	public function delete($key) {
		apcu_delete($key);
	}

	public function contains($key) {
		return apcu_exists($key);
	}

	public function increment($key, $offset = 1) {
		return apcu_inc($key, $offset);
	}

	public function decrement($key, $offset = 1) {
		return apcu_dec($key, $offset);
	}
}