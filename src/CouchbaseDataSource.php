<?php
require_once("NoSQLDataSource.php");

/**
 * Encapsulates couchbase server connection & bucket data.
*/
class CouchbaseDataSource implements NoSQLDataSource  {
	private $host;
	
	private $userName;
	private $password;
	
	private $bucketName;
	private $bucketPassword;
	
	public function setHost($strHost) {
		$this->host = $strHost;
	}
		
	public function getHost() {
		return $this->host;
	}
	
	public function setAuthenticationInfo($strUsername, $strPassword) {
		$this->userName = $strUsername;
		$this->password = $strPassword;
	}
	
	public function getUserName() {
		return $this->userName;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setBucketInfo($bucketName, $bucketPassword="") {
		$this->bucketName = $bucketName;
		$this->bucketPassword = $bucketPassword;
	}
	
	public function getBucketName() {
		return $this->bucketName;
	}
	
	public function getBucketPassword() {
		return $this->bucketPassword;
	}
}