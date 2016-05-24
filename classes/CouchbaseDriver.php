<?php
/**
 * Defines couchbase implementation of nosql operations.
 *
 * DOCS: http://www.couchbase.com/communities/php/getting-started
 */
class CouchbaseDriver implements NoSQLDBDriver {
	/**
	 * @var Couchbase
	 */
	private $objConnection;

	public function __construct($strServer, $intPort, $strBucket, $strUsername="", $strPassword="") {
		$this->objConnection = new Couchbase($strServer.":".$intPort, $strUsername, $strPassword, $strBucket);
	}

	public function add($key, $value, $expiration=0) {
		return $this->objConnection->add($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		return $this->objConnection->set($key, $value, $expiration);
	}

	public function get($key) {
		return $this->objConnection->get($key);
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