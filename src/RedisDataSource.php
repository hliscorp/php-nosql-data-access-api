<?php
require_once("NoSQLDataSource.php");
require_once("NoSQLServerDataSource.php");

/**
 * Encapsulates a data source to use for redis connections.
 */
class RedisDataSource extends NoSQLServerDataSource implements NoSQLDataSource {}