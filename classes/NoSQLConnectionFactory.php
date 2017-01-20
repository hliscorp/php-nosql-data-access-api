<?php
/**
 * Implements a singleton factory for multiple NoSQL servers connection.
 */
final class NoSQLConnectionFactory {
	/**
	 * Stores open connections.
	 * 
	 * @var array
	 */
	private static $instances;
	
	/**
	 * Stores registered data sources.
	 * @var array
	 */
	private static $dataSources;
	
    /**
     * @var NoSQLDBServer
     */
    private $database_connection = null;
	
	/**
	 * Registers a data source object encapsulatings connection info based on unique server identifier.
	 * 
	 * @param string $strServerName Unique identifier of server you will be connecting to.
	 * @param NoSQLDataSource $objDataSource
	 */
	public static function setDataSource($strServerName, NoSQLDataSource $objDataSource){
		self::$dataSources[$strServerName] = $objDataSource;
	}
	
	/**
	 * Opens connection to database server (if not already open) according to NoSQLDataSource and 
	 * returns an object of that connection to delegate operations to.
	 * 
	 * @param string $strServerName Unique identifier of server you will be connecting to.
	 * @throws NoSQLConnectionException
	 * @return NoSQLDBOperations
	 */
	public static function getInstance($strServerName){
        if(isset(self::$instances[$strServerName])) {
            return self::$instances[$strServerName];
        }
        self::$instances[$strServerName] = new NoSQLConnectionFactory($strServerName);
		return self::$instances[$strServerName];
	}


	/**
	 * Connects to database automatically.
	 *
	 * @throws NoSQLConnectionException
	 */
	private function __construct($strServerName) {
		if(!isset(self::$dataSources[$strServerName])) throw new NoSQLConnectionException("Datasource not set for: ".$strServerName);
		$className = str_replace("DataSource","Driver",get_class(self::$dataSources[$strServerName]));
		if(!class_exists($className)) throw new NoSQLConnectionException("Class not found: ".$className);
		$this->database_connection = new $className();
		$this->database_connection->connect(self::$dataSources[$strServerName]);
	}
	
	/**
	 * Internal utility to get connection.
	 *
	 * @return NoSQLDBOperations
	 */
	private function getConnection() {
		return $this->database_connection;
	}
	
	/**
	 * Disconnects from database server automatically.
	 */
	public function __destruct() {
		try {
			$this->database_connection->disconnect();
		} catch(Exception $e) {}
	}
	
}