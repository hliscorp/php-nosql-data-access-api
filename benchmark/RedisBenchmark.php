<?php
require_once("../src/RedisDriver.php");
require_once("AbstractBenchmark.php");

class RedisBenchmark extends AbstractBenchmark{
	protected function getDataSource() {
		$dataSource = new RedisDataSource();
		$dataSource->addServer("127.0.0.1");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new RedisDriver();
	}
}

new RedisBenchmark();