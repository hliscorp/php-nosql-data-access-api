<?php
require_once("RedisDataSource.php");
require_once("NoSQLDBOperations.php");
require_once("NoSQLDBServer.php");

/**
 * Defines redis implementation of nosql operations.
 *
 * DOCS: https://github.com/nicolasff/phpredis/blob/master/README.markdown#connect-open
 */
class RedisDriver implements NoSQLDBOperations, NoSQLDBServer {
	/**
	 * @var Redis
	 */
	private $objConnection;

	public function connect(NoSQLDataSource $dataSource) {
		$objRedis = new Redis();
		$objRedis->connect($dataSource->getHost(), $dataSource->getPort()); // always returns true (so makes no sense checking), also throws no exception
		$this->objConnection = $objRedis;
	}
	
	public function disconnect() {
		$this->objConnection->close();
	}

	public function add($key, $value, $expiration=0) {
		$this->objConnection->set($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		if($expiration==0) {
			$this->objConnection->set($key, $value);
		} else {
			$this->objConnection->setex($key, $expiration, $value);
		}
	}

	public function get($key) {
		return $this->objConnection->get($key);
	}
	
	public function contains($key) {
		return $this->objConnection->exists($key);
	}

	public function delete($key) {
		$this->objConnection->delete($key);
	}

	public function increment($key, $offset=1) {
		if($offset==1) {
			return $this->objConnection->incr($key);
		} else {
			return $this->objConnection->incrBy($key, $offset);
		}
	}

	public function decrement($key, $offset=1) {
		if($offset==1) {
			return $this->objConnection->decr($key);
		} else {
			return $this->objConnection->decrBy($key, $offset);
		}
	}
}