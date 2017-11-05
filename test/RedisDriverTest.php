<?php
require_once("../src/RedisDriver.php");
require_once("AbstractTest.php");

class RedisDriverTest extends AbstractTest {
	protected function getDataSource() {
		$dataSource = new RedisDataSource();
		$dataSource->addServer("127.0.0.1");
		return $dataSource;
	}
	
	protected function getDriver() {
		return new RedisDriver();
	}
	
	protected function testOperations(NoSQLDriver $driver) {
		try {
			$driver->set("a","b");
			echo "SET: OK"."\n";
		} catch(OperationFailedException $e) {
			echo "SET: NOK"."\n";
		}
		
		echo "GET: ".($driver->get("a")=="b"?"OK":"NOK")."\n";
		try {
			$driver->get("x");
			echo "GET: NOK"."\n";
		} catch(KeyNotFoundException $e) {
			echo "GET: OK"."\n";
		}
		
		echo "CONTAINS: ".($driver->contains("a")?"OK":"NOK")."\n";
		echo "CONTAINS: ".($driver->contains("x")?"NOK":"OK")."\n";
		
		$driver->delete("a");
		echo "DELETE: ".($driver->contains("a")?"NOK":"OK")."\n";
		try {
			$driver->delete("x");
			echo "DELETE: NOK"."\n";
		} catch(KeyNotFoundException $e) {
			echo "DELETE: OK"."\n";
		}
		
		echo "INCREMENT: ".($driver->increment("counter")==1?"OK":"NOK")."\n";
		
		echo "DECREMENT: ".($driver->decrement("counter")==0?"OK":"NOK")."\n";
		
		try {
			$driver->flush();
			echo "FLUSH: ".($driver->contains("counter")?"NOK":"OK")."\n";
		} catch(OperationFailedException $e) {
			echo "FLUSH: NOK"."\n";
		}
	}
}

new RedisDriverTest();