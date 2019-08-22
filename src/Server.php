<?php
namespace Lucinda\NoSQL;

/**
 * Defines connection operations to a NoSQL server
 */
interface Server
{
    /**
     * Connects to nosql provider
     *
     * @param DataSource $dataSource
     * @throws ConfigurationException If developer misconfigures data source.
     * @throws ConnectionException If connection to database server fails.
     */
    public function connect(DataSource $dataSource);
    
    /**
     * Disconnects from nosql provider
     */
    public function disconnect();
}
