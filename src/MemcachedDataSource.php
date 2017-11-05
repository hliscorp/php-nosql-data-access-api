<?php
require_once("NoSQLDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections.
 */
class MemcachedDataSource implements NoSQLDataSource  {
	private $servers = array();
	
	public function addServer($strHost, $intPort = 11211) {
		$this->servers[$strHost] = $intPort;
	}
	
	
	public function getServers() {
		return $this->servers;
	}
}