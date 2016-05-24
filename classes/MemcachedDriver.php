<?php
/**
 * Defines memcached implementation of nosql operations.
 */
class MemcachedDriver implements NoSQLDBDriver {
	/**
	 * @var Memcached
	 */
	private $objConnection;

	public function __construct($tblHostsAndPorts) {
		$memcache = new Memcached();
		foreach($tblHostsAndPorts as $memcacheServer=>$intPort) {
			$memcache->addServer($memcacheServer, $intPort); // always returns true (so makes no sense checking), also throws no exception
		}
		$this->objConnection = $memcache;
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