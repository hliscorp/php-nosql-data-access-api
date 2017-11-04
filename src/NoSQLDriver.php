<?php
/**
 * Defines common operations in a nosql database
 */
interface NoSQLDriver {	
	/**
	 * Sets value to store that will be accessible by key.
	 * 
	 * @param string $key Key based on which value will be accessible.
	 * @param mixed $value Value to store.
	 * @param integer $expiration Time to live in seconds until expiration (0: never expires)
	 * @throws OperationFailedException If operation didn't succeed.
	 */
	public function set($key, $value, $expiration=0);
	
	/**
	 * Gets value by key.
	 * 
	 * @param string $key Key based on which value will be searched.
	 * @return mixed Resulting value.
	 * @throws KeyNotFoundException If key doesn't exist in store.
	 * @throws OperationFailedException If operation didn't succeed.
	 */
	public function get($key);
	
	/**
	 * Deletes value by key.
	 * 
	 * @param string $key Key based on which value will be searched.
	 * @throws KeyNotFoundException If key doesn't exist in store.
	 * @throws OperationFailedException If operation didn't succeed.
	 */
	public function delete($key);

	/**
	 * Checks if key to access value from exists.
	 *
	 * @param string $key Key based on which value will be searched.
	 * @return boolean
	 */
	public function contains($key);
	
	/**
	 * Increments a counter by key.
	 * 
	 * @param string $key Key based on which counter will be accessible from
	 * @param integer $offset Incrementation step.
	 * @return integer Incremented value (value of offset if key originally did not exist)
	 * @throws KeyNotFoundException If key doesn't exist in store.
	 * @throws OperationFailedException If operation didn't succeed.
	 */
	public function increment($key, $offset = 1);
	
	/**
	 * Decrements a counter by key.
	 * 
	 * @param string $key Key based on which counter will be accessible from
	 * @param integer $offset Decrementation step.
	 * @return integer Decremented value (value of offset if key originally did not exist)
	 * @throws KeyNotFoundException If key doesn't exist in store.
	 * @throws OperationFailedException If operation didn't succeed.
	 */
	public function decrement($key, $offset = 1);
	
	/**
	 * Flushes DB of all keys.
	 */
	public function flush();
}