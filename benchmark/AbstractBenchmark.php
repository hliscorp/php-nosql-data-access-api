<?php
abstract class AbstractBenchmark {
	public function __construct() {
		$driver = $this->getDriver();
		if($driver instanceof NoSQLServer) {
			$driver->connect($this->getDataSource());
		}
		$this->testOperations($driver);
		if($driver instanceof NoSQLServer) {
			$driver->disconnect();
		}
	}
	abstract protected function getDataSource();
	abstract protected function getDriver();
		
	private function testOperations(NoSQLDriver $driver) {
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->set("a".$i, $i);
		}
		echo "SET: ".(microtime(true)-$start)."\n";
		
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->get("a".$i);
		}
		echo "GET: ".(microtime(true)-$start)."\n";
				
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->contains("a".$i);
		}
		echo "CONTAINS: ".(microtime(true)-$start)."\n";
		
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->delete("a".$i);
		}
		echo "DELETE: ".(microtime(true)-$start)."\n";
		
		$driver->set("counter",0);
		
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->increment("counter");
		}
		echo "INCREMENT: ".(microtime(true)-$start)."\n";
				
		$start = microtime(true);
		for($i=0;$i<100000;++$i) {
			$driver->decrement("counter");
		}
		echo "DECREMENT: ".(microtime(true)-$start)."\n";
		
		$driver->delete("counter");
	}
}