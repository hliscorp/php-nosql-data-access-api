<?php
/**
 * Encapsulates a data source to use for memcached connections.
 */
class MemcachedDataSource extends NoSQLDataSource  {
	private $strHost;
	private $intPort;
	
	/**
	 * Sets database server host name
	 *
	 * @param string $strHost
	 * @return void
	 */
	public function setHost($strHost) {
		$this->strHost = $strHost;
	}
	
	/**
	 * Gets database server host name
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->strHost;
	}
	
	/**
	 * Sets database server port
	 *
	 * @param integer $intPort
	 * @return void
	 */
	public function setPort($intPort) {
		$this->intPort = $intPort;
	}
	
	/**
	 * Gets database server port
	 *
	 * @return integer
	 */
	public function getPort() {
		return $this->intPort;
	}
}