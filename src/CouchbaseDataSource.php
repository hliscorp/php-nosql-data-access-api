<?php
namespace Lucinda\NoSQL;

require_once("DataSource.php");

/**
 * Encapsulates couchbase server connection & bucket data.
*/
class CouchbaseDataSource implements DataSource  {
	private $host;
	
	private $userName;
	private $password;
	
	private $bucketName;
	private $bucketPassword;
	
	/**
	 * Sets Couchbase cluster host name (or list of hosts separated by commas)
	 * 
	 * @param string $strHost
	 */
	public function setHost($strHost) {
		$this->host = $strHost;
	}
		
	/**
	 * Gets Couchbase cluster host name.
	 * 
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}
	
	/**
	 * Sets credentials required by connection to server.
	 * 
	 * @param string $strUsername
	 * @param string $strPassword
	 */
	public function setAuthenticationInfo($strUsername, $strPassword) {
		$this->userName = $strUsername;
		$this->password = $strPassword;
	}
	
	/**
	 * Gets value of username necessary for connection credentials.
	 * 
	 * @return string
	 */
	public function getUserName() {
		return $this->userName;
	}
	
	
	/**
	 * Gets value of password necessary for connection credentials.
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Sets information about couchbase bucket connection will be using (optionally with a password as well)
	 * 
	 * @param string $bucketName
	 * @param string $bucketPassword
	 */
	public function setBucketInfo($bucketName, $bucketPassword="") {
		$this->bucketName = $bucketName;
		$this->bucketPassword = $bucketPassword;
	}
	
	/**
	 * Gets name of bucket that holds your key-value store.
	 * 
	 * @return string
	 */
	public function getBucketName() {
		return $this->bucketName;
	}
	
	/**
	 * Gets password necessary to access bucket
	 * 
	 * @return string|null
	 */
	public function getBucketPassword() {
		return $this->bucketPassword;
	}
}