<?php
namespace Lucinda\NoSQL;

require_once("exceptions/ConnectionException.php");
require_once("RedisDataSource.php");
require_once("Driver.php");
require_once("Server.php");

/**
 * Defines redis implementation of nosql operations.
 *
 * DOCS: https://github.com/nicolasff/phpredis/blob/master/README.markdown#connect-open
 */
class RedisDriver implements Driver, Server {
	/**
	 * @var \Redis
	 */
	private $connection;

	public function connect(DataSource $dataSource) {
		if(!$dataSource instanceof RedisDataSource) throw new ConnectionException("Invalid data source type");
		$servers = $dataSource->getServers();
		if(empty($servers)) throw new ConnectionException("No servers are set!");
		$redis = null;
		if(sizeof($servers)>1) {
			$serverList = array();
			foreach($servers as $name=>$port) {
				$serverList[] = $name.":".$port;
			}
			$redis = new \RedisCluster(NULL, $serverList, $dataSource->getTimeout(), $dataSource->isPersistent());
		} else {
			$port = reset($servers);
			$host = key($servers);
			$redis = new \Redis();
			if($dataSource->isPersistent()) {
				$redis->pconnect($host, $port, $dataSource->getTimeout());
			} else {
				$redis->connect($host, $port, $dataSource->getTimeout());
			}
		}
		
		$this->connection = $redis;
	}
	
	public function disconnect() {
		$this->connection->close();
	}

	public function set($key, $value, $expiration=0) {
		$result = null;
		if($expiration==0) {
			$result = $this->connection->set($key, $value);
		} else {
			$result = $this->connection->setex($key, $expiration, $value);
		}
		if(!$result) {
			throw new OperationFailedException($this->connection->getLastError());
		}
	}

	public function get($key) {
		$result = $this->connection->get($key);
		if($result === false) {
			if(!$this->connection->exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($this->connection->getLastError());
			}
		}
		return $result;
	}
	
	public function contains($key) {
		return $this->connection->exists($key);
	}

	public function delete($key) {
		$result = $this->connection->delete($key);
		if(!$result) {
			if(!$this->connection->exists($key)) {
				throw new KeyNotFoundException($key);
			} else {
				throw new OperationFailedException($this->connection->getLastError());
			}
		}
	}

	public function increment($key, $offset=1) {
		$result = null;
		if($offset==1) {
			$result = $this->connection->incr($key);
		} else {
			$result = $this->connection->incrBy($key, $offset);
		}
		if($result===FALSE) {	
			// driver automatically creates not found key as "0"
			throw new OperationFailedException($this->connection->getLastError());
		}
		return $result;
	}

	public function decrement($key, $offset=1) {
		$result = null;
		if($offset==1) {
			$result = $this->connection->decr($key);
		} else {
			$result = $this->connection->decrBy($key, $offset);
		}
		if($result===FALSE) {
			// driver automatically creates not found key as "-1"
			throw new OperationFailedException($this->connection->getLastError());
		}
		return $result;
	}
	
	public function flush() {
		$result = $this->connection->flushAll();
		if(!$result) {
			throw new OperationFailedException($this->connection->getLastError());
		}
	}
	
	
	/**
	 * Gets a pointer to native wrapped object for advanced operations.
	 *
	 * @return \Redis|\RedisCluster
	 */
	public function getDriver() {
		return $this->connection;
	}
}