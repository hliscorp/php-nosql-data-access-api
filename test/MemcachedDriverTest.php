<?php
require_once("../src/MemcachedDriver.php");
require_once("AbstractTest.php");

class MemcachedDriverTest extends AbstractTest {
	protected function getDataSource() {
		$dataSource = new MemcachedDataSource();
		$dataSource->addServer("127.0.0.1");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new MemcachedDriver();
	}
}

new MemcachedDriverTest();