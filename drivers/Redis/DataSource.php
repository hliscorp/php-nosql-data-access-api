<?php
namespace Lucinda\NoSQL\Vendor\Redis;

/**
 * Encapsulates a data source to use for redis connections.
 */
class DataSource extends \Lucinda\NoSQL\ServerDataSource implements \Lucinda\NoSQL\DataSource
{
    /**
     * Gets default port specific to no-sql vendor.
     *
     * @return integer
     */
    protected function getDefaultPort(): int
    {
        return 6379;
    }
}
