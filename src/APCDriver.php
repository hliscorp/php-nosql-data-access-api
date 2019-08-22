<?php
namespace Lucinda\NoSQL;

require_once("APCDataSource.php");
require_once("Driver.php");

/**
 * Defines APC implementation of nosql operations.
 */
class APCDriver implements Driver
{
    /**
     * {@inheritDoc}
     * @see Driver::set()
     */
    public function set($key, $value, $expiration=0)
    {
        $result = apc_store($key, $value, $expiration);
        if (!$result) {
            throw new OperationFailedException();
        }
    }

    /**
     * {@inheritDoc}
     * @see Driver::get()
     */
    public function get($key)
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
     * {@inheritDoc}
     * @see Driver::delete()
     */
    public function delete($key)
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
     * {@inheritDoc}
     * @see Driver::contains()
     */
    public function contains($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritDoc}
     * @see Driver::increment()
     */
    public function increment($key, $offset = 1)
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
     * {@inheritDoc}
     * @see Driver::decrement()
     */
    public function decrement($key, $offset = 1)
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
     * {@inheritDoc}
     * @see Driver::flush()
     */
    public function flush()
    {
        apc_clear_cache(); // returns true always
    }
}
