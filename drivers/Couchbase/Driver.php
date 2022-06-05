<?php

namespace Lucinda\NoSQL\Vendor\Couchbase;

use Lucinda\NoSQL\ConfigurationException;
use Lucinda\NoSQL\ConnectionException;
use Lucinda\NoSQL\OperationFailedException;
use Lucinda\NoSQL\KeyNotFoundException;
use Lucinda\NoSQL\DataSource;

/**
 * Defines couchbase implementation of nosql operations.
 */
class Driver implements \Lucinda\NoSQL\Driver, \Lucinda\NoSQL\Server
{
    /**
     * @var \CouchbaseBucket
     */
    private \CouchbaseBucket $bucket;

    /**
     * Connects to nosql provider
     *
     * @param  DataSource $dataSource
     * @throws ConfigurationException If developer mis-configures data source.
     * @throws ConnectionException If connection to database server fails.
     */
    public function connect(DataSource $dataSource): void
    {
        if (!$dataSource instanceof \Lucinda\NoSQL\Vendor\Couchbase\DataSource) {
            throw new ConfigurationException("Invalid data source type");
        }
        if (!$dataSource->getHost() || !$dataSource->getBucketName() || !$dataSource->getUserName() || !$dataSource->getPassword()) {
            throw new ConfigurationException("Insufficient settings");
        }

        try {
            $authenticator = new \Couchbase\PasswordAuthenticator();
            $authenticator->username($dataSource->getUserName())->password($dataSource->getPassword());

            $cluster = new \CouchbaseCluster("couchbase://".$dataSource->getHost());
            $cluster->authenticate($authenticator);

            if ($dataSource->getBucketPassword()) {
                $this->bucket = $cluster->openBucket($dataSource->getBucketName(), $dataSource->getBucketPassword());
            } else {
                $this->bucket = $cluster->openBucket($dataSource->getBucketName());
            }
        } catch (\CouchbaseException $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    /**
     * Sets value to store that will be accessible by key.
     *
     * @param  string  $key        Key based on which value will be accessible.
     * @param  mixed   $value      Value to store.
     * @param  integer $expiration Time to live in seconds until expiration (0: never expires)
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function set(string $key, $value, int $expiration=0): void
    {
        $flags = array();
        if ($expiration) {
            $flags["expiry"] = $expiration;
        }
        try {
            $this->bucket->upsert($key, $value, $flags);
        } catch (\CouchbaseException $e) {
            throw new OperationFailedException($e->getMessage());
        }
    }

    /**
     * Checks if key to access value from exists.
     *
     * @param  string $key Key based on which value will be searched.
     * @return boolean
     */
    public function contains(string $key): bool
    {
        try {
            $this->bucket->get($key);
            return true;
        } catch (\CouchbaseException $e) {
            return false;
        }
    }

    /**
     * Gets value by key.
     *
     * @param  string $key Key based on which value will be searched.
     * @return mixed Resulting value.
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function get(string $key): mixed
    {
        try {
            $result = $this->bucket->get($key);
            return $result->value;
        } catch (\CouchbaseException $e) {
            if (strpos($e->getMessage(), "LCB_KEY_ENOENT")!==false) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($e->getMessage());
            }
        }
    }

    /**
     * Increments a counter by key.
     *
     * @param  string  $key    Key based on which counter will be accessible from
     * @param  integer $offset Incrementation step.
     * @return integer Incremented value (value of offset if key originally did not exist)
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function increment(string $key, int $offset = 1): int
    {
        try {
            $result = $this->bucket->counter($key, $offset);
            return $result->value;
        } catch (\CouchbaseException $e) {
            if (strpos($e->getMessage(), "LCB_KEY_ENOENT")!==false) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($e->getMessage());
            }
        }
    }

    /**
     * Decrements a counter by key.
     *
     * @param  string  $key    Key based on which counter will be accessible from
     * @param  integer $offset Decrementation step.
     * @return integer Decremented value (value of offset if key originally did not exist)
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function decrement(string $key, int $offset = 1): int
    {
        try {
            $result = $this->bucket->counter($key, -$offset);
            return $result->value;
        } catch (\CouchbaseException $e) {
            if (strpos($e->getMessage(), "LCB_KEY_ENOENT")!==false) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($e->getMessage());
            }
        }
    }

    /**
     * Deletes value by key.
     *
     * @param  string $key Key based on which value will be searched.
     * @throws KeyNotFoundException If key doesn't exist in store.
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function delete(string $key): void
    {
        try {
            $this->bucket->remove($key);
        } catch (\CouchbaseException $e) {
            if (strpos($e->getMessage(), "LCB_KEY_ENOENT")!==false) {
                throw new KeyNotFoundException($key);
            } else {
                throw new OperationFailedException($e->getMessage());
            }
        }
    }

    /**
     * Flushes DB of all keys.
     *
     * @throws OperationFailedException If operation didn't succeed.
     */
    public function flush(): void
    {
        try {
            $this->bucket->manager()->flush();
        } catch (\CouchbaseException $e) {
            throw new OperationFailedException($e->getMessage());
        }
    }

    /**
     * Gets a pointer to native wrapped object for advanced operations.
     *
     * @return \CouchbaseBucket
     */
    public function getDriver(): \CouchbaseBucket
    {
        return $this->bucket;
    }

    /**
     * Disconnects from nosql provider
     */
    public function disconnect(): void
    {
        // driver does not support manual disconnect
    }
}
