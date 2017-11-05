<?php
require_once("../src/APCuDriver.php");
require_once("AbstractBenchmark.php");
ini_set('memory_limit', '2048M');

class APCuBenchmark extends AbstractBenchmark{
	protected function getDataSource() {
		return new APCuDataSource();
	}
	
	protected function getDriver() {
		return new APCuDriver();
	}
}

new APCuBenchmark();