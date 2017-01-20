<?php
require_once("CouchbaseDataSource.php");
require_once("NoSQLDBOperations.php");
require_once("NoSQLDBServer.php");

/**
 * Defines couchbase implementation of nosql operations.
 *
 * DOCS: http://www.couchbase.com/communities/php/getting-started
 */
class CouchbaseDriver implements NoSQLDBOperations, NoSQLDBServer {
	/**
	 * @var Couchbase
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof CouchbaseDataSource) throw new NoSQLConnectionException("Invalid data source type");
		$this->objConnection = new Couchbase($dataSource->getHost().":".$dataSource->getPort(), $dataSource->getUserName(), $dataSource->getPassword());
		$objBucketInfo = $dataSource->getBucketInfo();
		if($objBucketInfo) {
			$this->objConnection->openBucket($objBucketInfo->getName(), $objBucketInfo->getPassword());
		}
	}
	
	public function disconnect() {
		$this->objConnection->disconnect();
	}

	public function add($key, $value, $expiration=0) {
		$this->objConnection->add($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		$this->objConnection->set($key, $value, $expiration);
	}

	public function get($key) {
		return $this->objConnection->get($key);
	}
	
	public function contains($key) {
		return ($this->objConnection->get($key) == NULL && $this->objConnection->getResultCode() == COUCHBASE_KEY_ENOENT?false:true);
	}

	public function delete($key) {
		$this->objConnection->delete($key);
	}

	public function increment($key, $offset = 1) {
		return $this->objConnection->increment($key, $offset);
	}

	public function decrement($key, $offset = 1) {
		return $this->objConnection->decrement($key, $offset);
	}
}