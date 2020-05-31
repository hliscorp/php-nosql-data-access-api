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
     * ServerDataSource constructor.
     * @param \SimpleXMLElement $databaseInfo
     * @throws ConfigurationException
     */
    public function __construct(\SimpleXMLElement $databaseInfo)
    {
        // set host and ports
        $temp = (string) $databaseInfo["host"];
        if (!$temp) {
            throw new ConfigurationException("Attribute 'host' is mandatory for 'server' tag");
        }
        $hosts = explode(",", $temp);
        foreach ($hosts as $hostAndPort) {
            $hostAndPort = trim($hostAndPort);
            $position = strpos($hostAndPort, ":");
            if ($position!==false) {
                $this->servers[substr($hostAndPort, 0, $position)] = (int) substr($hostAndPort, $position+1);
            } else {
                $this->servers[$hostAndPort] = $this->getDefaultPort();
            }
        }

        // set timeout
        $this->timeout = (int) $databaseInfo["timeout"];

        // set persistent
        $this->persistent = ((string) $databaseInfo["persistent"]?true:false);
    }

    /**
     * Gets server host:port combinations to connect to
     *
     * @return array
     */
    public function getServers(): array
    {
        return $this->servers;
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
