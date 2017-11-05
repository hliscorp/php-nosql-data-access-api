<?php
require_once("exceptions/NoSQLConnectionException.php");
require_once("MemcacheDataSource.php");
require_once("NoSQLDriver.php");
require_once("NoSQLServer.php");

/**
 * Defines memcache implementation of nosql operations.
 */
class MemcacheDriver implements NoSQLDriver, NoSQLServer {
	/**
	 * @var Memcache
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof MemcacheDataSource) throw new NoSQLConnectionException("Invalid data source type");
		$memcache = new Memcache();
		$servers = $dataSource->getServers();
		if(empty($servers)) throw new NoSQLConnectionException("No servers are set!");
		foreach($servers as $host=>$port) {
			$memcache->addServer($host, $port);
		}		 
		$this->objConnection = $memcache;
	}
	
	public function disconnect() {
		$this->objConnection->close();
	}

	public function set($key, $value, $expiration=0) {
		$result = $this->objConnection->set($key, $value, 0, $expiration);
		if(!$result) {
			throw new OperationFailedException();
		}
	}

	public function get($key) {
		$result = $this->objConnection->get($key); // driver makes it impossible to distinguish between false and failure
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}
	
	public function contains($key) {
		return ($this->objConnection->get($key)!==false?true:false);
	}

	public function delete($key) {
		$result = $this->objConnection->delete($key);
		if(!$result) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
	}

	public function increment($key, $offset = 1) {
		$result = $this->objConnection->increment($key, $offset);
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}

	public function decrement($key, $offset = 1) {
		$result = $this->objConnection->decrement($key, $offset);
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}
	
	public function flush() {
		$this->objConnection->flush();
		if(!$result) {
			throw new OperationFailedException();
		}
	}
	
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return Memcache
	 */
	public function getDriver() {
		return $this->objConnection;
	}
}