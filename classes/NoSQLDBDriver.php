<?php
/**
 * Defines common operations in a nosql database
 */
interface NoSQLDBDriver {
	public function add($key, $value, $expiration=0);
	public function set($key, $value, $expiration=0);
	public function get($key);
	public function delete($key);
	public function increment($key, $offset = 1);
	public function decrement($key, $offset = 1);
}