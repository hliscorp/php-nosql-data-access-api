<?php
abstract class AbstractTest {
	public function __construct() {
		$driver = $this->getDriver();
		if($driver instanceof NoSQLServer) {
			$this->testConnect($driver);
		}
		$this->testOperations($driver);
		if($driver instanceof NoSQLServer) {
			$this->testDisconnect($driver);
		}
	}
	abstract protected function getDataSource();
	abstract protected function getDriver();
	
	private function testConnect(NoSQLServer $driver) {
		$dataSource = $this->getDataSource();
		try {
			$driver->connect($dataSource);
			echo "CONNECT: OK"."\n";
		} catch(Exception $e) {
			echo "CONNECT: NOK (".$e->getMessage().")";
			die();
		}
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
		
		try {
			$driver->increment("counter");
			echo "INCREMENT: NOK"."\n";
		} catch(KeyNotFoundException $e) {
			$driver->set("counter",0);
			echo "INCREMENT: OK"."\n";
		}
		echo "INCREMENT: ".($driver->increment("counter")==1?"OK":"NOK")."\n";
		
		
		try {
			$driver->decrement("x");
			echo "DECREMENT: NOK"."\n";
		} catch(KeyNotFoundException $e) {
			echo "DECREMENT: OK"."\n";
		}
		echo "DECREMENT: ".($driver->decrement("counter")==0?"OK":"NOK")."\n";
		
		try {
			$driver->flush();
			echo "FLUSH: ".($driver->contains("counter")?"NOK":"OK")."\n";
		} catch(OperationFailedException $e) {
			echo "FLUSH: NOK"."\n";
		}
	}
	
	private function testDisconnect(NoSQLServer $driver) {
		try {
			$driver->disconnect();
			echo "DISCONNECT: OK"."\n";
		} catch(Exception $e) {
			echo "DISCONNECT: NOK (".$e->getMessage().")";
		}
	}
}