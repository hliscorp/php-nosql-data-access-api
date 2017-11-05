<?php
require_once("../src/APCDriver.php");
require_once("AbstractBenchmark.php");

class APCBenchmark extends AbstractBenchmark{
	protected function getDataSource() {
		return new APCDataSource();
	}
	
	protected function getDriver() {
		return new APCDriver();
	}
}

new APCBenchmark();