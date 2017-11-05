<?php
require_once("../src/CouchbaseDriver.php");
require_once("AbstractTest.php");

class CouchbaseDriverTest extends AbstractTest {
	protected function getDataSource() {
		$dataSource = new CouchbaseDataSource();
		$dataSource->setHost("127.0.0.1");
		$dataSource->setAuthenticationInfo("{USERNAME}", "{PASSWORD}");
		$dataSource->setBucketInfo("test");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new CouchbaseDriver();
	}
}

new CouchbaseDriverTest();