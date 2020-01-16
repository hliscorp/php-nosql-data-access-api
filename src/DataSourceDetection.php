<?php
namespace Lucinda\NoSQL;

use Lucinda\NoSQL\Vendor\APC\DataSource as APCDataSource;
use Lucinda\NoSQL\Vendor\APCu\DataSource as APCuDataSource;
use Lucinda\NoSQL\Vendor\Couchbase\DataSource as CouchbaseDataSource;
use Lucinda\NoSQL\Vendor\Memcache\DataSource as MemcacheDataSource;
use Lucinda\NoSQL\Vendor\Memcached\DataSource as MemcachedDataSource;
use Lucinda\NoSQL\Vendor\Redis\DataSource as RedisDataSource;

/**
 * Encapsulates data source detection (itself encapsulating database server settings) from an XML tag
 */
class DataSourceDetection
{
    private $dataSource;

    /**
     * DataSourceDetection constructor.
     * @param \SimpleXMLElement $databaseInfo XML tag containing data source info.
     * @throws ConfigurationException
     */
    public function __construct(\SimpleXMLElement $databaseInfo)
    {
        $this->setDataSource($databaseInfo);
    }

    /**
     * Detects data source (itself encapsulating database server settings) from an XML tag
     *
     * @param \SimpleXMLElement $databaseInfo
     * @return mixed
     * @throws ConfigurationException
     */
    protected function setDataSource(\SimpleXMLElement $databaseInfo): void
    {
        $driver = (string)$databaseInfo["driver"];
        if (!$driver) {
            throw new ConfigurationException("Child tag 'driver' is mandatory for 'server' tags");
        }
        switch ($driver) {
            case "couchbase":
                $this->dataSource = new CouchbaseDataSource($databaseInfo);
                break;
            case "memcache":
                $this->dataSource = new MemcacheDataSource($databaseInfo);
                break;
            case "memcached":
                $this->dataSource = new MemcachedDataSource($databaseInfo);
                break;
            case "redis":
                $this->dataSource = new RedisDataSource($databaseInfo);
                break;
            case "apc":
                $this->dataSource = new APCDataSource();
                break;
            case "apcu":
                $this->dataSource = new APCuDataSource();
                break;
            default:
                throw new ConfigurationException("NoSQL driver not supported: " . $driver);
                break;
        }
    }

    /**
     * Gets detected data source
     *
     * @return DataSource
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }
}
