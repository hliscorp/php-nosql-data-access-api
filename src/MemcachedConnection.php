<?php
require_once("MemcachedDataSource.php");

/**
 * Defines memcached implementation of nosql operations.
 */
class MemcachedConnection implements NoSQLConnection, NoSQLServer {
	/**
	 * @var Memcached
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof MemcachedDataSource) throw new NoSQLConnectionException("Invalid data source type");
		$memcache = new Memcached();
		$memcache->addServer($dataSource->getHost(), $dataSource->getPort()); 
		$this->objConnection = $memcache;
	}
	
	public function disconnect() {
		$this->objConnection->quit();
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
		return ($this->objConnection->get($key)!==false?true:false);
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