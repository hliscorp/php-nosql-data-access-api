<?php
namespace Lucinda\NoSQL\Vendor\APC;

use \Lucinda\NoSQL\OperationFailedException;
use \Lucinda\NoSQL\KeyNotFoundException;

/**
 * Defines APC implementation of nosql operations.
 */
class Driver implements \Lucinda\NoSQL\Driver
{
    /**
     * Sets value to store that will be accessible by key.
     *
     * @param string $key Key based on which value will be accessible.
     * @param mixed $value Value to store.
     * @param integer $expiration Time to live in seconds until expiration (0: never expires)
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function set(string $key, $value, int $expiration=0): void
    {
        $result = apc_store($key, $value, $expiration);
        if (!$result) {
            throw new OperationFailedException();
        }
    }

    /**
     * Checks if key to access value from exists.
     *
     * @param string $key Key based on which value will be searched.
     * @return boolean
     */
    public function contains(string $key): bool
    {
        return apc_exists($key);
    }

    /**
     * Gets value by key.
     *
     * @param string $key Key based on which value will be searched.
     * @return mixed Resulting value.
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function get(string $key)
    {
        $result = apc_fetch($key);
        if ($result===false) {
            if (!apc_exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException();
            }
        }
        return $result;
    }

    /**
     * Increments a counter by key.
     *
     * @param string $key Key based on which counter will be accessible from
     * @param integer $offset Incrementation step.
     * @return integer Incremented value (value of offset if key originally did not exist)
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function increment(string $key, int $offset = 1): int
    {
        $result = apc_inc($key, $offset);
        if ($result===false) {
            if (!apc_exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException();
            }
        }
        return $result;
    }

    /**
     * Decrements a counter by key.
     *
     * @param string $key Key based on which counter will be accessible from
     * @param integer $offset Decrementation step.
     * @return integer Decremented value (value of offset if key originally did not exist)
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function decrement(string $key, int $offset = 1): int
    {
        $result = apc_dec($key, $offset);
        if ($result===false) {
            if (!apc_exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException();
            }
        }
        return $result;
    }

    /**
     * Deletes value by key.
     *
     * @param string $key Key based on which value will be searched.
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function delete(string $key): void
    {
        $result = apc_delete($key);
        if (!$result) {
            if (!apc_exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException();
            }
        }
    }
    
    /**
     * Flushes DB of all keys.
     */
    public function flush(): void
    {
        apc_clear_cache(); // returns true always
    }
}
