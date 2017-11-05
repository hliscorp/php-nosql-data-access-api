<?php
require_once("../src/MemcachedDriver.php");
require_once("AbstractBenchmark.php");

class MemcachedBenchmark extends AbstractBenchmark{
	protected function getDataSource() {
		$dataSource = new MemcachedDataSource();
		$dataSource->addServer("127.0.0.1");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new MemcachedDriver();
	}
}

new MemcachedBenchmark();