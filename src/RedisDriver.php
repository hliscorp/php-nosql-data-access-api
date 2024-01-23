<?php
namespace Lucinda\NoSQL;

require("RedisDataSource.php");

/**
 * Defines redis implementation of nosql operations.
 *
 * DOCS: https://github.com/nicolasff/phpredis/blob/master/README.markdown#connect-open
 */
class RedisDriver implements Driver, Server
{
    /**
     * @var \Redis
     */
    private $connection;

    /**
     * {@inheritDoc}
     * @see Server::connect()
     */
    public function connect(DataSource $dataSource)
    {
        if (!$dataSource instanceof RedisDataSource) {
            throw new ConfigurationException("Invalid data source type");
        }
        $servers = $dataSource->getServers();
        if (empty($servers)) {
            throw new ConfigurationException("No servers are set!");
        }
        $redis = null;
        
        if (sizeof($servers)>1) {
            try {
                $serverList = array();
                foreach ($servers as $name=>$port) {
                    $serverList[] = $name.":".$port;
                }
                $redis = new \RedisCluster(null, $serverList, (float) $dataSource->getTimeout(), $dataSource->isPersistent());
            } catch (\RedisClusterException $e) {
                throw new ConnectionException($e->getMessage());
            }
        } else {
            try {
                $port = reset($servers);
                $host = key($servers);
                $redis = new \Redis();
                if ($dataSource->isPersistent()) {
                    $result = $redis->pconnect($host, $port, (float) $dataSource->getTimeout());
                    if (!$result) {
                        throw new ConnectionException();
                    }
                } else {
                    $result = $redis->connect($host, $port, (float) $dataSource->getTimeout());
                    if (!$result) {
                        throw new ConnectionException();
                    }
                }
            } catch (\RedisException $e) {
                throw new ConnectionException($e->getMessage());
            }
        }
        
        $this->connection = $redis;
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
        $result = null;
        if ($expiration==0) {
            $result = $this->connection->set($key, $value);
        } else {
            $result = $this->connection->setex($key, $expiration, $value);
        }
        if (!$result) {
            throw new OperationFailedException($this->connection->getLastError());
        }
    }

    /**
     * {@inheritDoc}
     * @see Driver::get()
     */
    public function get($key)
    {
        $result = $this->connection->get($key);
        if ($result === false) {
            if (!$this->connection->exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($this->connection->getLastError());
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::contains()
     */
    public function contains($key)
    {
        return $this->connection->exists($key);
    }

    /**
     * {@inheritDoc}
     * @see Driver::delete()
     */
    public function delete($key)
    {
        $result = $this->connection->delete($key);
        if (!$result) {
            if (!$this->connection->exists($key)) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($this->connection->getLastError());
            }
        }
    }

    /**
     * {@inheritDoc}
     * @see Driver::increment()
     */
    public function increment($key, $offset=1)
    {
        $result = null;
        if ($offset==1) {
            $result = $this->connection->incr($key);
        } else {
            $result = $this->connection->incrBy($key, $offset);
        }
        if ($result===false) {
            // driver automatically creates not found key as "0"
            throw new OperationFailedException($this->connection->getLastError());
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see Driver::decrement()
     */
    public function decrement($key, $offset=1)
    {
        $result = null;
        if ($offset==1) {
            $result = $this->connection->decr($key);
        } else {
            $result = $this->connection->decrBy($key, $offset);
        }
        if ($result===false) {
            // driver automatically creates not found key as "-1"
            throw new OperationFailedException($this->connection->getLastError());
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::flush()
     */
    public function flush()
    {
        $result = $this->connection->flushAll();
        if (!$result) {
            throw new OperationFailedException($this->connection->getLastError());
        }
    }
    
    /**
     * Gets a pointer to native wrapped object for advanced operations.
     *
     * @return \Redis|\RedisCluster
     */
    public function getDriver()
    {
        return $this->connection;
    }
}
