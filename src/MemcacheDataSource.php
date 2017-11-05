<?php
require_once("NoSQLDataSource.php");
require_once("NoSQLServerDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections via memcache driver.
 */
class MemcacheDataSource extends NoSQLServerDataSource implements NoSQLDataSource {
	protected function getDefaultPort(){
		return 11211;
	}
}