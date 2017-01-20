<?php
/**
 * Defines operations in connecting to single NoSQL server.
 */
interface NoSQLDBServer {
	/**
	 * Connects to nosql provider
	 *
	 * @param NoSQLDataSource $dataSource
	 */
	public function connect(NoSQLDataSource $dataSource);
	
	/**
	 * Disconnects from nosql provider
	 */
	public function disconnect();
}