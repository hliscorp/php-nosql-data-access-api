<?php
require_once("../src/APCDriver.php");
require_once("AbstractTest.php");

class APCDriverTest extends AbstractTest {
	protected function getDataSource() {
		return new APCDataSource();
	}
	
	protected function getDriver() {
		return new APCDriver();
	}
}

new APCDriverTest();