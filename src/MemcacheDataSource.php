<?php
namespace Lucinda\NoSQL;

require_once("DataSource.php");
require_once("ServerDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections via memcache driver.
 */
class MemcacheDataSource extends ServerDataSource implements DataSource {
	protected function getDefaultPort(){
		return 11211;
	}
}