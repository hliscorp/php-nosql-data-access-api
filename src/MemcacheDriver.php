<?php
require_once("MemcacheDataSource.php");

/**
 * Defines memcache implementation of nosql operations.
 */
class MemcacheDriver implements NoSQLConnection, NoSQLServer {
	/**
	 * @var Memcache
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof MemcacheDataSource) throw new NoSQLConnectionException("Invalid data source type");
		$memcache = new Memcache();
		$memcache->addServer($dataSource->getHost(), $dataSource->getPort()); 
		$this->objConnection = $memcache;
	}
	
	public function disconnect() {
		$this->objConnection->close();
	}

	public function add($key, $value, $expiration=0) {
		$this->objConnection->add($key, $value, false, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		$this->objConnection->set($key, $value, 0, $expiration);
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