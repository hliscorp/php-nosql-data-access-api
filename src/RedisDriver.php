<?php
require_once("RedisDataSource.php");

/**
 * Defines redis implementation of nosql operations.
 *
 * DOCS: https://github.com/nicolasff/phpredis/blob/master/README.markdown#connect-open
 */
class RedisDriver implements NoSQLDriver, NoSQLServer {
	/**
	 * @var Redis
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		if(!$dataSource instanceof RedisDataSource) throw new NoSQLConnectionException("Invalid data source type");
		$servers = $dataSource->getServers();
		if(empty($servers)) throw new NoSQLConnectionException("No servers are set!");
		$objRedis = null;
		if(sizeof($servers)>1) {
			$serverList = array();
			foreach($servers as $name=>$port) {
				$serverList[] = $name.":".$port;
			}
			$objRedis = new RedisCluster(NULL, $serverList, $dataSource->getTimeout(), $dataSource->isPersistent());
		} else {
			$port = reset($servers);
			$host = key($servers);
			$objRedis = new Redis();
			if($dataSource->isPersistent()) {
				$objRedis->pconnect($host, $port, $dataSource->getTimeout());
			} else {
				$objRedis->connect($host, $port, $dataSource->getTimeout());
			}
		}
		
		$this->objConnection = $objRedis;
	}
	
	public function disconnect() {
		$this->objConnection->close();
	}

	public function set($key, $value, $expiration=0) {
		$result = null;
		if($expiration==0) {
			$result = $this->objConnection->set($key, $value);
		} else {
			$result = $this->objConnection->setex($key, $expiration, $value);
		}
		if(!$result) {
			throw new OperationFailedException($this->objConnection->getLastError());
		}
	}

	public function get($key) {
		$result = $this->objConnection->get($key);
		if($result === false) {
			if(!$this->objConnection->exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($this->objConnection->getLastError());
			}
		}
		return $result;
	}
	
	public function contains($key) {
		return $this->objConnection->exists($key);
	}

	public function delete($key) {
		$result = $this->objConnection->delete($key);
		if(!$result) {
			if(!$this->objConnection->exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($this->objConnection->getLastError());
			}
		}
	}

	public function increment($key, $offset=1) {
		$result = null;
		if($offset==1) {
			$result = $this->objConnection->incr($key);
		} else {
			$result = $this->objConnection->incrBy($key, $offset);
		}
		if($result===FALSE) {
			throw new OperationFailedException($this->objConnection->getLastError());
		}
		return $result;
	}

	public function decrement($key, $offset=1) {
		$result = null;
		if($offset==1) {
			$result = $this->objConnection->decr($key);
		} else {
			$result = $this->objConnection->decrBy($key, $offset);
		}
		if($result===FALSE) {	// driver automatically creates not found key as "0"
			throw new OperationFailedException($this->objConnection->getLastError());
		}
		return $result;
	}
	
	public function flush() {
		$result = $this->objConnection->flushAll();
		if(!$result) {	// driver automatically creates not found key as "-1"
			throw new OperationFailedException($this->objConnection->getLastError());
		}
	}
	
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return Redis|RedisCluster
	 */
	public function getDriver() {
		return $this->objConnection;
	}
}