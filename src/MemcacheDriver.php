<?php
namespace Lucinda\NoSQL;

require_once("exceptions/ConnectionException.php");
require_once("MemcacheDataSource.php");
require_once("Driver.php");
require_once("Server.php");

/**
 * Defines memcache implementation of nosql operations.
 */
class MemcacheDriver implements Driver, Server {
	/**
	 * @var \Memcache
	 */
	private $connection;

	public function connect(DataSource $dataSource) {
		if(!$dataSource instanceof MemcacheDataSource) throw new ConnectionException("Invalid data source type");
		$memcache = new \Memcache();
		$servers = $dataSource->getServers();
		if(empty($servers)) throw new ConnectionException("No servers are set!");
		foreach($servers as $host=>$port) {
			$memcache->addServer($host, $port, $dataSource->isPersistent(), 1, ($dataSource->getTimeout()?$dataSource->getTimeout():1));
		}		 
		$this->connection = $memcache;
	}
	
	public function disconnect() {
		$this->connection->close();
	}

	public function set($key, $value, $expiration=0) {
		$result = $this->connection->set($key, $value, 0, $expiration);
		if(!$result) {
			throw new OperationFailedException();
		}
	}

	public function get($key) {
		$result = $this->connection->get($key); // driver makes it impossible to distinguish between false and failure
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}
	
	public function contains($key) {
		return ($this->connection->get($key)!==false?true:false);
	}

	public function delete($key) {
		$result = $this->connection->delete($key);
		if(!$result) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
	}

	public function increment($key, $offset = 1) {
		$result = $this->connection->increment($key, $offset);
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}

	public function decrement($key, $offset = 1) {
		$result = $this->connection->decrement($key, $offset);
		if($result===false) {
			throw new KeyNotFoundException($key); // driver doesn't allow checking if key exists, so by default key not found is assumed
		}
		return $result;
	}
	
	public function flush() {
		$this->connection->flush();
		if(!$result) {
			throw new OperationFailedException();
		}
	}
	
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return \Memcache
	 */
	public function getDriver() {
		return $this->connection;
	}
}
