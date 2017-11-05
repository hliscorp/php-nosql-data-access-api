<?php
require_once("../src/MemcacheDriver.php");
require_once("AbstractBenchmark.php");

class MemcacheBenchmark extends AbstractBenchmark{
	protected function getDataSource() {
		$dataSource = new MemcacheDataSource();
		$dataSource->addServer("127.0.0.1");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new MemcacheDriver();
	}
}

new MemcacheBenchmark();