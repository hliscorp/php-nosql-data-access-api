<?php
require_once("exceptions/NoSQLConnectionException.php");
require_once("MemcachedDataSource.php");
require_once("NoSQLDriver.php");
require_once("NoSQLServer.php");

/**
 * Defines memcached implementation of nosql operations.
 */
class MemcachedDriver implements NoSQLDriver, NoSQLServer {
	const PERSISTENT_ID = "pid";
	/**
	 * @var Memcached
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof MemcachedDataSource) throw new NoSQLConnectionException("Invalid data source type");
		
		$servers = $dataSource->getServers();
		if(empty($servers)) throw new NoSQLConnectionException("No servers are set!");

        $memcached = ($dataSource->isPersistent()?new Memcached(self::PERSISTENT_ID):new Memcached());
		$memcached->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
		if($dataSource->getTimeout()) {
			$memcached->setOption(Memcached::OPT_RECV_TIMEOUT, $dataSource->getTimeout());
			$memcached->setOption(Memcached::OPT_SEND_TIMEOUT, $dataSource->getTimeout());
		}
		if(!$dataSource->isPersistent() || !count($memcached->getServerList())) {
			foreach($servers as $host=>$port) {
                $memcached->addServer($host, $port);
			}	
		}	 
		$this->objConnection = $memcached;
	}
	
	public function disconnect() {
		$this->objConnection->quit();
	}

	public function set($key, $value, $expiration=0) {
		$result = $this->objConnection->set($key, $value, $expiration);
		if(!$result) {
			$resultCode = $this->objConnection->getResultCode();
			throw new OperationFailedException((string) $resultCode);
		}
	}

	public function get($key) {
		$result = $this->objConnection->get($key);
		if($result===FALSE) {
			$resultCode = $this->objConnection->getResultCode();
			if(Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}
	
	public function contains($key) {
		$this->objConnection->get($key);
		return (Memcached::RES_NOTFOUND == $this->objConnection->getResultCode()?false:true);
	}

	public function delete($key) {
		$result = $this->objConnection->delete($key);
		if(!$result) {
			$resultCode = $this->objConnection->getResultCode();
			if(Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
	}

	public function increment($key, $offset = 1) {
		$result = $this->objConnection->increment($key, $offset);
		if($result===FALSE) {
			$resultCode = $this->objConnection->getResultCode();
			if(Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}

	public function decrement($key, $offset = 1) {
		$result = $this->objConnection->decrement($key, $offset);
		if($result===FALSE) {
			$resultCode = $this->objConnection->getResultCode();
			if(Memcached::RES_NOTFOUND == $resultCode) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException((string) $resultCode);
			}
		}
		return $result;
	}
	
	public function flush() {
		$result = $this->objConnection->flush();
		if(!$result) {
			$resultCode = $this->objConnection->getResultCode();
			throw new OperationFailedException((string) $resultCode);
		}
	}
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return Memcached
	 */
	public function getDriver() {
		return $this->objConnection;
	}
}