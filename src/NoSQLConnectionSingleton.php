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
     * @var NoSQLServer
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
	 * Opens connection to database server (if not already open) according to NoSQLDataSource and returns an object of that connection to delegate operations to. 
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
     * @throws NoSQLConnectionException
     */
    private function __construct() {
		if(!self::$dataSource) throw new NoSQLConnectionException("Datasource not set!");
		$className = str_replace("DataSource","Driver",get_class(self::$dataSource));
		if(!class_exists($className)) throw new NoSQLConnectionException("Class not found: ".$className);
		$this->database_connection = new $className();
        $this->database_connection->connect(self::$dataSource);
    }
    
    /**
     * Internal utility to get connection.
     * 
     * @return NoSQLConnection
     */
    private function getConnection() {
        return $this->database_connection;
    }
    
    /**
     * Disconnects from database server automatically.
     */
    public function __destruct() {
        try {
        	if($this->database_connection) {
            	$this->database_connection->disconnect();
        	}
        } catch(Exception $e) {}
    }
}
