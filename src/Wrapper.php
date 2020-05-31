<?php
namespace Lucinda\NoSQL;

use Lucinda\NoSQL\Vendor\APC\DataSource as APCDataSource;
use Lucinda\NoSQL\Vendor\APCu\DataSource as APCuDataSource;
use Lucinda\NoSQL\Vendor\Couchbase\DataSource as CouchbaseDataSource;
use Lucinda\NoSQL\Vendor\Memcache\DataSource as MemcacheDataSource;
use Lucinda\NoSQL\Vendor\Memcached\DataSource as MemcachedDataSource;
use Lucinda\NoSQL\Vendor\Redis\DataSource as RedisDataSource;

/**
 * Reads server tags from XML into DataSource objects and injects latter into ConnectionSingleton/ConnectionFactory classes
 * to be used in querying later on
 */
class Wrapper
{
    /**
     * Binds NoSQL Data Access API to XML based on development environment and sets DataSource for later querying
     *
     * @param \SimpleXMLElement $xml
     * @param string $developmentEnvironment
     * @throws ConfigurationException If XML is improperly configured.
     */
    public function __construct(\SimpleXMLElement $xml, $developmentEnvironment)
    {
        $xml = $xml->nosql->{$developmentEnvironment};
        if (!empty($xml)) {
            if (!$xml->server) {
                throw new ConfigurationException("Server not set for environment!");
            }
            $xml = (array) $xml;
            if (is_array($xml["server"])) {
                foreach ($xml["server"] as $element) {
                    if (!isset($element["name"])) {
                        throw new ConfigurationException("Attribute 'name' is mandatory for 'server' tag");
                    }
                    ConnectionFactory::setDataSource((string) $element["name"], $this->getDataSource($element));
                }
            } else {
                ConnectionSingleton::setDataSource($this->getDataSource($xml["server"]));
            }
        }
    }

    /**
     * Detects data source (itself encapsulating database server settings) from a XML 'server' tag
     *
     * @param \SimpleXMLElement $databaseInfo
     * @return DataSource
     * @throws ConfigurationException
     */
    private function getDataSource(\SimpleXMLElement $databaseInfo): DataSource
    {
        $driver = (string)$databaseInfo["driver"];
        if (!$driver) {
            throw new ConfigurationException("Child tag 'driver' is mandatory for 'server' tags");
        }
        switch ($driver) {
            case "couchbase":
                return new CouchbaseDataSource($databaseInfo);
                break;
            case "memcache":
                return new MemcacheDataSource($databaseInfo);
                break;
            case "memcached":
                return new MemcachedDataSource($databaseInfo);
                break;
            case "redis":
                return new RedisDataSource($databaseInfo);
                break;
            case "apc":
                return new APCDataSource();
                break;
            case "apcu":
                return new APCuDataSource();
                break;
            default:
                throw new ConfigurationException("NoSQL driver not supported: " . $driver);
                break;
        }
    }
}
