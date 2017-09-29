<?php
/**
 * Defines common operations in a nosql database
 */
interface NoSQLDriver {
	
	/**
	 * Adds value to store that will be accessible by key.
	 * 
	 * @param string $key Key based on which value will be accessible.
	 * @param mixed $value Value to store.
	 * @param integer $expiration Time to live in seconds until expiration (0: never expires)
	 */
	public function add($key, $value, $expiration=0);
	
	/**
	 * Sets value to store that will be accessible by key.
	 * 
	 * @param string $key Key based on which value will be accessible.
	 * @param mixed $value Value to store.
	 * @param integer $expiration Time to live in seconds until expiration (0: never expires)
	 */
	public function set($key, $value, $expiration=0);
	
	/**
	 * Gets value by key.
	 * 
	 * @param string $key Key based on which value will be searched.
	 * @return mixed Resulting value.
	 */
	public function get($key);
	
	/**
	 * Deletes value by key.
	 * 
	 * @param string $key Key based on which value will be searched.
	 */
	public function delete($key);

	/**
	 * Checks if key to access value from exists.
	 *
	 * @param string $key Key based on which value will be searched.
	 */
	public function contains($key);
	
	/**
	 * Increments a counter by key.
	 * 
	 * @param string $key Key based on which counter will be accessible from
	 * @param integer $offset Incrementation step.
	 * @return integer Incremented value (1 if key originally did not exist)
	 */
	public function increment($key, $offset = 1);
	
	/**
	 * Decrements a counter by key.
	 * 
	 * @param string $key Key based on which counter will be accessible from
	 * @param integer $offset Decrementation step.
	 * @return integer Decremented value (0 if key originally did not exist)
	 */
	public function decrement($key, $offset = 1);
	
	/**
	 * Flushes DB of all keys.
	 */
	public function flush();
}