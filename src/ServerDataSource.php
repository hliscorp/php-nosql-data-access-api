<?php
namespace Lucinda\NoSQL;

/**
 * Encapsulates operations to use on a NoSQL data source that requires a third party server installed
 */
abstract class ServerDataSource
{
    private $servers = array();
    private $timeout;
    private $persistent = false;
    
    /**
     * Adds server to connection pool.
     *
     * @param string $host Value of server host.
     * @param integer $port (optional) Value of server port. If not set, it will be replaced by default port specific to no-sql vendor.
     */
    public function addServer(string $host, int $port = 0): void
    {
        if (!$port) {
            $port = $this->getDefaultPort();
        }
        $this->servers[$host] = $port;
    }
    
    /**
     * Gets servers that take part of connection pool.
     *
     * @return array
     */
    public function getServers(): array
    {
        return $this->servers;
    }
    
    /**
     * Sets operations timeout.
     *
     * @param integer $seconds
     */
    public function setTimeout(int $seconds): void
    {
        $this->timeout = $seconds;
    }
    
    /**
     * Gets operations timeout.
     *
     * @return integer
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
    
    /**
     * Signals that client wants persistent connections.
     */
    public function setPersistent(): void
    {
        $this->persistent = true;
    }
    
    /**
     * Checks if client wanted connections to be persistent.
     *
     * @return boolean
     */
    public function isPersistent(): bool
    {
        return $this->persistent;
    }
    
    /**
     * Gets default port specific to no-sql vendor.
     *
     * @return integer
     */
    abstract protected function getDefaultPort(): int;
}
