<?php
require_once("CouchbaseDataSource.php");

/**
 * Defines couchbase implementation of nosql operations.
 */
class CouchbaseDriver implements NoSQLDriver, NoSQLServer {
	/**
	 * @var CouchbaseBucket
	 */
	private $objBucket;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof CouchbaseDataSource) throw new NoSQLConnectionException("Invalid data source type");
		if(!$dataSource->getHost() || !$dataSource->getBucketName() || !$dataSource->getUserName() || $dataSource->getPassword()) throw new NoSQLConnectionException("Insufficient settings");
		
		$authenticator = new \Couchbase\PasswordAuthenticator();
		$authenticator->username($dataSource->getUserName())->password($dataSource->getPassword());
		
		$cluster = new CouchbaseCluster("couchbase://".$dataSource->getHost());
		$cluster->authenticate($authenticator);
		$this->objBucket = $cluster->openBucket($dataSource->getBucketName(), $dataSource->getBucketPassword());
	}
	
	public function disconnect() {
		// driver does not support manual disconnect
	}

	public function set($key, $value, $expiration=0) {
		$flags = array();
		if($expiration) $flags["expiry"] = $expiration;
		try {
			$this->objBucket->upsert($key, $value, $flags);
		} catch(CouchbaseException $e) {
			throw new OperationFailedException($e->getMessage());
		}
	}

	public function get($key) {
		try {
			$result = $this->objBucket->get($key);
			return $result->value;
		} catch(CouchbaseException $e) {
			if(strpos($e->getMessage(),"LCB_KEY_ENOENT")!==false) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($e->getMessage());
			}
		}
	}
	
	public function contains($key) {
		try {
			$this->objBucket->get($key);
			return true;
		} catch(CouchbaseException $e) {
			return false;
		}
	}

	public function delete($key) {
		try {
			$this->objBucket->remove($key);
		} catch(CouchbaseException $e) {
			if(strpos($e->getMessage(),"LCB_KEY_ENOENT")!==false) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($e->getMessage());
			}
		}
	}

	public function increment($key, $offset = 1) {
		try {
			$result = $this->objBucket->counter($key, $offset);
			return $result->value;
		} catch(CouchbaseException $e) {
			if(strpos($e->getMessage(),"LCB_KEY_ENOENT")!==false) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($e->getMessage());
			}
		}
	}

	public function decrement($key, $offset = 1) {
		try {
			$result = $this->objBucket->counter($key, -$offset);
			return $result->value;
		} catch(CouchbaseException $e) {
			if(strpos($e->getMessage(),"LCB_KEY_ENOENT")!==false) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($e->getMessage());
			}
		}
	}
	
	public function flush() {
		try {
			$this->objBucket->manager()->flush();
		} catch(CouchbaseException $e) {
			throw new OperationFailedException($e->getMessage());
		}
	}
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 * 
	 * @return CouchbaseBucket
	 */
	public function getDriver() {
		return $this->objBucket;
	}
}