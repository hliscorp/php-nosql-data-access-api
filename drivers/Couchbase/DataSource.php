<?php
namespace Lucinda\NoSQL\Vendor\Couchbase;

use Lucinda\NoSQL\ConfigurationException;

/**
 * Encapsulates couchbase server connection & bucket data.
*/
class DataSource implements \Lucinda\NoSQL\DataSource
{
    private string $host;
    
    private string $userName;
    private string $password;
    
    private string $bucketName;
    private string $bucketPassword;


    /**
     * Gets couchbase server info from XML
     *
     * @param \SimpleXMLElement $databaseInfo
     * @throws ConfigurationException
     */
    public function __construct(\SimpleXMLElement $databaseInfo)
    {
        $host = (string) $databaseInfo["host"];
        $userName = (string) $databaseInfo["username"];
        $password = (string) $databaseInfo["password"];
        $bucket = (string) $databaseInfo["bucket_name"];
        if (!$host || !$userName || !$password || !$bucket) {
            throw new ConfigurationException("For COUCHBASE driver following attributes are mandatory: host, username, password, bucket_name");
        }

        $this->host = $host;
        $this->userName = $userName;
        $this->password = $password;
        $this->bucketName = $bucket;
        $this->bucketPassword = (string) $databaseInfo["bucket_password"];
    }

    /**
     * Gets Couchbase cluster host name.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }
    
    /**
     * Gets value of username necessary for connection credentials.
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
    
    
    /**
     * Gets value of password necessary for connection credentials.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * Gets name of bucket that holds your key-value store.
     *
     * @return string
     */
    public function getBucketName(): string
    {
        return $this->bucketName;
    }
    
    /**
     * Gets password necessary to access bucket
     *
     * @return string
     */
    public function getBucketPassword(): string
    {
        return $this->bucketPassword;
    }

    /**
     * Gets driver associated to data source
     *
     * @return Driver
     */
    public function getDriver(): \Lucinda\NoSQL\Driver
    {
        return new Driver();
    }
}
