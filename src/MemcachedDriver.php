<?php
namespace Lucinda\NoSQL;

require_once("exceptions/ConfigurationException.php");
require_once("exceptions/ConnectionException.php");
require_once("MemcachedDataSource.php");
require_once("Driver.php");
require_once("Server.php");

/**
 * Defines memcached implementation of nosql operations.
 */
class MemcachedDriver implements Driver, Server {
	const PERSISTENT_ID = "pid";
	/**
	 * @var \Memcached
	 */
	private $connection;

	/**
	 * {@inheritDoc}
	 * @see Server::connect()
	 */
	public function connect(DataSource $dataSource) {
	    if(!$dataSource instanceof MemcachedDataSource) {
	        throw new ConfigurationException("Invalid data source type");
	    }
		
		$servers = $dataSource->getServers();
		if(empty($servers)) {
		    throw new ConfigurationException("No servers are set!");
		}

        $memcached = ($dataSource->isPersistent()?new \Memcached(self::PERSISTENT_ID):new \Memcached());
		$memcached->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
		if($dataSource->getTimeout()) {
			$memcached->setOption(\Memcached::OPT_RECV_TIMEOUT, $dataSource->getTimeout());
			$memcached->setOption(\Memcached::OPT_SEND_TIMEOUT, $dataSource->getTimeout());
		}
		if(!$dataSource->isPersistent() || !count($memcached->getServerList())) {
			foreach($servers as $host=>$port) {
			    $memcached->addServer($host, $port);
			}
			// check connections
			$stats = $memcached->getStats();
			foreach($servers as $host=>$port) {
			    if(empty($stats[$host.":".$port])) {
			        throw new ConnectionException("Connection to host failed: ".$host.":".$port);
			    }
			}		 
		}	 
		$this->connection = $memcached;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Server::disconnect()
	 */
	public function disconnect() {
		$this->connection->quit();
	}

	/**
	 * {@inheritDoc}
	 * @see Driver::set()
	 */
	public function set($key, $value, $expiration=0) {
		$result = $this->connection->set($key, $value, $expiration);
		if(!$result) {
			$resultCode = $this->connection->getResultCode();
			throw new OperationFailedException((string) $resultCode);
		}
	}

	/**
	 * {@inheritDoc}
	 * @see Driver::get()
	 */
	public function get($key) {
		$result = $this->connection->get($key);
		if($result===FALSE) {
			$resultCode = $this->connection->getResultCode();
			if(\Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Driver::contains()
	 */
	public function contains($key) {
		$this->connection->get($key);
		return (\Memcached::RES_NOTFOUND == $this->connection->getResultCode()?false:true);
	}

	/**
	 * {@inheritDoc}
	 * @see Driver::delete()
	 */
	public function delete($key) {
		$result = $this->connection->delete($key);
		if(!$result) {
			$resultCode = $this->connection->getResultCode();
			if(\Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 * @see Driver::increment()
	 */
	public function increment($key, $offset = 1) {
		$result = $this->connection->increment($key, $offset);
		if($result===FALSE) {
			$resultCode = $this->connection->getResultCode();
			if(\Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see Driver::decrement()
	 */
	public function decrement($key, $offset = 1) {
		$result = $this->connection->decrement($key, $offset);
		if($result===FALSE) {
			$resultCode = $this->connection->getResultCode();
			if(\Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Driver::flush()
	 */
	public function flush() {
		$result = $this->connection->flush();
		if(!$result) {
			$resultCode = $this->connection->getResultCode();
			throw new OperationFailedException((string) $resultCode);
		}
	}
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return \Memcached
	 */
	public function getDriver() {
		return $this->connection;
	}
}