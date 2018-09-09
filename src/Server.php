<?php
namespace Lucinda\NoSQL;

/**
 * Defines connection operations to a NoSQL server
 */
interface Server {
	/**
	 * Connects to nosql provider
	 *
	 * @param DataSource $dataSource
	 */
	public function connect(DataSource $dataSource);
	
	/**
	 * Disconnects from nosql provider
	 */
	public function disconnect();
}