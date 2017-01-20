<?php
/**
 * Encapsulates bucket information.
 */
class BucketInformation {
	private $name;
	private $password;
	
	public function __construct($name, $password) {
		$this->name = $name;
		$this->password = $password;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getPassword() {
		return $this->password;
	}
}

/**
 * Implements a data source.
*/
class CouchbaseDataSource extends NoSQLDataSource {
	private $strHost;
	private $intPort;
	private $strUserName;
	private $strPassword;
	
	private $objBucketInfo;
	
	/**
	 * Sets server host name
	 *
	 * @param string $strHost
	 * @return void
	 */
	public function setHost($strHost) {
		$this->strHost = $strHost;
	}
	
	/**
	 * Gets server host name
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->strHost;
	}
	
	/**
	 * Sets server port
	 *
	 * @param integer $intPort
	 * @return void
	 */
	public function setPort($intPort) {
		$this->intPort = $intPort;
	}
	
	/**
	 * Gets server port
	 *
	 * @return integer
	 */
	public function getPort() {
		return $this->intPort;
	}
	
	public function setBucketInfo($strName, $strPassword="") {
		$this->objBucketInfo = new BucketInformation($strName, $strPassword);
	}
	
	public function getBucketInfo() {
		return $this->objBucketInfo;
	}

	/**
	 * Sets database server user name
	 *
	 * @param string $strUserName
	 * @return void
	 */
	public function setUserName($strUserName){
		$this->strUserName = $strUserName;
	}

	/**
	 * Gets server user name
	 *
	 * @return string
	 */
	public function getUserName() {
		return $this->strUserName;
	}

	/**
	 * Sets server user password
	 *
	 * @param string $strPassword
	 * @return void
	 */
	public function setPassword($strPassword) {
		$this->strPassword = $strPassword;
	}

	/**
	 * Gets server user password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->strPassword;
	}
}