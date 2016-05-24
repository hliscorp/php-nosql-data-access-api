<?php
/**
 * Defines redis implementation of nosql operations.
 *
 * DOCS: https://github.com/nicolasff/phpredis/blob/master/README.markdown#connect-open
 */
class RedisDriver implements NoSQLDBDriver {
	private $tblConnections = array();

	public function __construct($tblHostsAndPorts) {
		foreach($tblHostsAndPorts as $strServerName=>$intPort) {
			$objRedis = new Redis();
			$objRedis->connect($strServerName, $intPort); // always returns true (so makes no sense checking), also throws no exception
			$this->tblConnections[]=$objRedis;
		}
	}

	public function add($key, $value, $expiration=0) {
		$this->set($key, $value, $expiration);
	}

	public function set($key, $value, $expiration=0) {
		foreach($this->tblConnections as $objConnection) {
			if($expiration==0) {
				$objConnection->set($key, $value);
			} else {
				$objConnection->setex($key, $expiration, $value);
			}
		}
	}

	public function get($key) {
		return $this->tblConnections[0]->get($key);
	}

	public function delete($key) {
		foreach($this->tblConnections as $objConnection) {
			$objConnection->delete($key);
		}
	}

	public function increment($key, $offset=1) {
		$intResponse = 0;
		foreach($this->tblConnections as $objConnection) {
			if($offset==1) {
				$intResponse = $objConnection->incr($key);
			} else {
				$intResponse = $objConnection->incrBy($key, $offset);
			}
		}
		return $intResponse;
	}

	public function decrement($key, $offset=1) {
		$intResponse = 0;
		foreach($this->tblConnections as $objConnection) {
			if($offset==1) {
				$intResponse = $objConnection->decr($key);
			} else {
				$intResponse = $objConnection->decrBy($key, $offset);
			}
		}
		return $intResponse;
	}
}