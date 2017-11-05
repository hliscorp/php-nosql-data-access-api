<?php
require_once("../src/CouchbaseDriver.php");
require_once("AbstractBenchmark.php");

class CouchbaseBenchmark extends AbstractBenchmark{
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

new CouchbaseBenchmark();