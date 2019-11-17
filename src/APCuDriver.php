<?php
namespace Lucinda\NoSQL;

require("APCuDataSource.php");

/**
 * Defines APCu implementation of nosql operations.
*/
class APCuDriver implements Driver
{
    /**
     * {@inheritDoc}
     * @see Driver::set()
     */
    public function set($key, $value, $expiration=0)
    {
        $result = apcu_store($key, $value, $expiration);
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
        $result = apcu_fetch($key);
        if ($result===false) {
            if (!apcu_exists($key)) {
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
        $result = apcu_delete($key);
        if (!$result) {
            if (!apcu_exists($key)) {
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
        return apcu_exists($key);
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::increment()
     */
    public function increment($key, $offset = 1)
    {
        $result = apcu_inc($key, $offset);
        if ($result===false) {
            throw new OperationFailedException();
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::decrement()
     */
    public function decrement($key, $offset = 1)
    {
        $result = apcu_dec($key, $offset);
        if ($result===false) {
            throw new OperationFailedException();
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::flush()
     */
    public function flush()
    {
        apcu_clear_cache(); // returns true always
    }
}
