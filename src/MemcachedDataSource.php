<?php
namespace Lucinda\NoSQL;

require_once("DataSource.php");
require_once("ServerDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections via memcached driver.
 */
class MemcachedDataSource extends ServerDataSource implements DataSource
{
    /**
     * {@inheritDoc}
     * @see ServerDataSource::getDefaultPort()
     */
    protected function getDefaultPort()
    {
        return 11211;
    }
}
