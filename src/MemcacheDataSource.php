<?php
namespace Lucinda\NoSQL;

require("ServerDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections via memcache driver.
 */
class MemcacheDataSource extends ServerDataSource implements DataSource
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
