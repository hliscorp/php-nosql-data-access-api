<?php
require_once("NoSQLDataSource.php");
require_once("NoSQLServerDataSource.php");

/**
 * Encapsulates a data source to use for memcached connections via memcached driver.
 */
class MemcachedDataSource extends NoSQLServerDataSource implements NoSQLDataSource  {}