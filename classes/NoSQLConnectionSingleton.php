<?php
/**
 * Implements a database connection singleton on top of NoSQLConnection object. Useful when your application works with only one database server.
 */
final class NoSQLConnectionSingleton
{
    /**
     * @var DataSource
     */
    private static $dataSource = null;
    
    /**
     * @var NoSQLConnectionSingleton
     */
    private static $instance = null;
    
    /**
     * @var NoSQLDBServer
     */
    private $database_connection = null;
    
    /**
     * Registers a data source object encapsulatings connection info.
     * 
     * @param NoSQLDataSource $dataSource
     */
    public static function setDataSource(NoSQLDataSource $dataSource) {
        self::$dataSource = $dataSource;
    }
        
    /**
	 * Opens connection to database server (if not already open) according to NoSQLDataSource and returns a NoSQLConnection object. 
     * 
     * @return NoSQLConnection
     */
    public static function getInstance()   {
        if(self::$instance) {
            return self::$instance->getConnection();
        }
        self::$instance = new NoSQLConnectionSingleton();
        return self::$instance->getConnection();
    }
    
    /**
     * Connects to database automatically.
     * 
     * @throws NoSQLException
     */
    private function __construct() {
		if(!self::$dataSource) throw new NoSQLException("Datasource not set!");
		$this->database_connection = null;
		if(self::$dataSource instanceof CouchbaseDataSource) {
		}
        $this->database_connection = new NoSQLConnection();
        $this->database_connection->connect(self::$dataSource);
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
