<?php
require_once("APCuDataSource.php");
require_once("NoSQLDriver.php");

/**
 * Defines APCu implementation of nosql operations.
*/
class APCuDriver implements NoSQLDriver {
	public function set($key, $value, $expiration=0) {
		$result = apcu_store($key, $value, $expiration);
		if(!$result) {
			throw new OperationFailedException();
		}
	}
	
	public function get($key) {
		$result = apcu_fetch($key);
		if($result===FALSE) {
			if(!apcu_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
		return $result;
	}
	
	public function delete($key) {
		$result = apcu_delete($key);
		if(!$result) {
			if(!apcu_exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException();
			}
		}
	}
	
	public function contains($key) {
		return apcu_exists($key);
	}
	
	public function increment($key, $offset = 1) {
		$result = apcu_inc($key, $offset);
		if($result===FALSE) {
			throw new OperationFailedException();
		}
		return $result;
	}
	
	public function decrement($key, $offset = 1) {
		$result = apcu_dec($key, $offset);
		if($result===FALSE) {
			throw new OperationFailedException();
		}
		return $result;
	}
	
	public function flush() {
		apcu_clear_cache(); // returns true always
	}
}