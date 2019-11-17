<?php
namespace Lucinda\NoSQL;

require("ServerDataSource.php");

/**
 * Encapsulates a data source to use for redis connections.
 */
class RedisDataSource extends ServerDataSource implements DataSource
{
    /**
     * {@inheritDoc}
     * @see ServerDataSource::getDefaultPort()
     */
    protected function getDefaultPort()
    {
        return 6379;
    }
}
