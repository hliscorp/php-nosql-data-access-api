<?php
namespace Lucinda\NoSQL;

require("MemcacheDataSource.php");

/**
 * Defines memcache implementation of nosql operations.
 */
class MemcacheDriver implements Driver, Server
{
    /**
     * @var \Memcache
     */
    private $connection;

    /**
     * {@inheritDoc}
     * @see Server::connect()
     */
    public function connect(DataSource $dataSource)
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
     * {@inheritDoc}
     * @see Server::disconnect()
     */
    public function disconnect()
    {
        $this->connection->close();
    }

    /**
     * {@inheritDoc}
     * @see Driver::set()
     */
    public function set($key, $value, $expiration=0)
    {
        $result = $this->connection->set($key, $value, 0, $expiration);
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
        $result = $this->connection->get($key); // driver makes it impossible to distinguish between false and failure
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::contains()
     */
    public function contains($key)
    {
        return ($this->connection->get($key)!==false?true:false);
    }

    /**
     * {@inheritDoc}
     * @see Driver::delete()
     */
    public function delete($key)
    {
        $result = $this->connection->delete($key);
        if (!$result) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
    }

    /**
     * {@inheritDoc}
     * @see Driver::increment()
     */
    public function increment($key, $offset = 1)
    {
        $result = $this->connection->increment($key, $offset);
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see Driver::decrement()
     */
    public function decrement($key, $offset = 1)
    {
        $result = $this->connection->decrement($key, $offset);
        if ($result===false) {
            throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
        }
        return $result;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see Driver::flush()
     */
    public function flush()
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
    public function getDriver()
    {
        return $this->connection;
    }
}
