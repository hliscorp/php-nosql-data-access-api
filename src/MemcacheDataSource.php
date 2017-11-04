<?php
/**
 * Encapsulates a data source to use for memcache connections.
 */
class MemcacheDataSource extends NoSQLDataSource  {
	private $servers = array();
		
	public function addServer($strHost, $intPort = 11211) {
		$this->servers[$strHost] = $intPort;
	}
	
	
	public function getServers() {
		return $this->servers;
	}
}