<?php
namespace Lucinda\NoSQL\Vendor\Memcache;

use \Lucinda\NoSQL\ConfigurationException;
use \Lucinda\NoSQL\ConnectionException;
use \Lucinda\NoSQL\OperationFailedException;
use \Lucinda\NoSQL\KeyNotFoundException;
use \Lucinda\NoSQL\DataSource;
use \Lucinda\NoSQL\Vendor\Memcache\DataSource as MemcacheDataSource;

/**
 * Defines memcache implementation of nosql operations.
 */
class Driver implements \Lucinda\NoSQL\Driver, \Lucinda\NoSQL\Server
{
    /**
     * @var \Memcache
     */
    private \Memcache $connection;

    /**
     * Connects to nosql provider
     *
     * @param DataSource $dataSource
     * @throws ConfigurationException If developer misconfigures data source.
     * @throws ConnectionException If connection to database server fails.
     */
    public function connect(DataSource $dataSource): void
    {
        if (!$dataSource instanceof MemcacheDataSource) {
            throw new ConfigurationException("Invalid data source type");
        }
        $memcache = new \Memcache();
        $servers = $dataSource->getServers();
        if (empty($servers)) {
            throw new ConfigurationException("No servers are set!");
        }
        foreach ($servers as $host=>$port) {
            $memcache->addServer($host, $port, $dataSource->isPersistent(), 1, ($dataSource->getTimeout()?$dataSource->getTimeout():1));
        }
        // check connections
        $stats = $memcache->getExtendedStats();
        foreach ($servers as $host=>$port) {
            if (empty($stats[$host.":".$port])) {
                throw new ConnectionException("Connection to host failed: ".$host.":".$port);
            }
        }
        $this->connection = $memcache;
    }

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
        $result = $this->connection->set($key, $value, 0, $expiration);
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
        return ($this->connection->get($key)!==false?true:false);
    }

    /**
     * Gets value by key.
     *
     * @param string $key Key based on which value will be searched.
     * @return mixed Resulting value.
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function get(string $key): mixed
    {
        $result = $this->connection->get($key); // driver makes it impossible to distinguish between false and failure
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
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
     */
    public function increment(string $key, int $offset = 1): int
    {
        $result = $this->connection->increment($key, $offset);
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
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
     */
    public function decrement(string $key, int $offset = 1): int
    {
        $result = $this->connection->decrement($key, $offset);
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
        return $result;
    }

    /**
     * Deletes value by key.
     *
     * @param string $key Key based on which value will be searched.
     * @throws KeyNotFoundException If key doesn't exist in store.
     */
    public function delete(string $key): void
    {
        $result = $this->connection->delete($key);
        if (!$result) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
    }

    /**
     * Flushes DB of all keys.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function flush(): void
    {
        $result = $this->connection->flush();
        if (!$result) {
            throw new OperationFailedException();
        }
    }
    
    /**
     * Gets a pointer to native wrapped object for advanced operations.
     *
     * @return \Memcache
     */
    public function getDriver(): \Memcache
    {
        return $this->connection;
    }

    /**
     * Disconnects from nosql provider
     */
    public function disconnect(): void
    {
        $this->connection->close();
    }
}
