<?php
require_once("NoSQLDataSource.php");

/**
 * Encapsulates a data source to use for redis connections.
 */
class RedisDataSource implements NoSQLDataSource {
	private $servers = array();
	
	private $timeout;
	private $persistent = false;
	
	public function addServer($strHost, $intPort = 6379) {
		$this->servers[$strHost] = $intPort;
	}
		
	public function getServers() {
		return $this->servers;
	}
	
	public function setTimeout($intSeconds) {
		$this->timeout = $intSeconds;
	}
	
	public function getTimeout() {
		return $this->timeout;
	}
	
	public function setPersistent() {
		$this->persistent = true;
	}
	
	public function isPersistent() {
		return $this->persistent;
	}
}