<?php
namespace Lucinda\NoSQL\Vendor\Couchbase;

/**
 * Encapsulates couchbase server connection & bucket data.
*/
class DataSource implements \Lucinda\NoSQL\DataSource
{
    private $host;
    
    private $userName;
    private $password;
    
    private $bucketName;
    private $bucketPassword;
    
    /**
     * Sets Couchbase cluster host name (or list of hosts separated by commas)
     *
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
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
     * Sets credentials required by connection to server.
     *
     * @param string $username
     * @param string $password
     */
    public function setAuthenticationInfo(string $username, string $password): void
    {
        $this->userName = $username;
        $this->password = $password;
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
     * Sets information about couchbase bucket connection will be using (optionally with a password as well)
     *
     * @param string $bucketName
     * @param string $bucketPassword
     */
    public function setBucketInfo(string $bucketName, string $bucketPassword=""): void
    {
        $this->bucketName = $bucketName;
        $this->bucketPassword = $bucketPassword;
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
}
