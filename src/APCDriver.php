<?php
require_once("APCDataSource.php");
require_once("NoSQLDriver.php");

/**
 * Defines APC implementation of nosql operations.
 */
class APCDriver implements NoSQLDriver {
	public function set($key, $value, $expiration=0) {
		$result = apc_store($key, $value, $expiration);
		if(!$result) {
			throw new OperationFailedException();
		}
	}

	public function get($key) {
		$result = apc_fetch($key);
		if($result===FALSE) {
			if(!apc_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
		return $result;
	}

	public function delete($key) {
		$result = apc_delete($key);
		if(!$result) {
			if(!apc_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
	}
	
	public function contains($key) {
		return apc_exists($key);
	}

	public function increment($key, $offset = 1) {
		$result = apc_inc($key, $offset);
		if($result===FALSE) {
			if(!apc_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
		return $result;
	}

	public function decrement($key, $offset = 1) {
		$result = apc_dec($key, $offset);
		if($result===FALSE) {
			if(!apc_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
		return $result;
	}
	
	public function flush() {
		apc_clear_cache(); // returns true always
	}
}