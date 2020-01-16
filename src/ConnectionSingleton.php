<?php
namespace Lucinda\NoSQL;

/**
 * Implements a database connection singleton on top of NoSQLConnection object. Useful when your application works with only one database server.
 */
class ConnectionSingleton
{
    /**
     * @var DataSource
     */
    private static $dataSource = null;
    
    /**
     * @var ConnectionSingleton
     */
    private static $instance = null;
    
    /**
     * @var Driver
     */
    private $database_connection = null;
    
    /**
     * Registers a data source object encapsulatings connection info.
     *
     * @param DataSource $dataSource
     */
    public static function setDataSource(DataSource $dataSource): void
    {
        self::$dataSource = $dataSource;
    }
        
    /**
     * Opens connection to database server (if not already open) according to DataSource and returns an object of that connection to delegate operations to.
     *
     * @return Driver
     */
    public static function getInstance(): Driver
    {
        if (!self::$instance) {
            self::$instance = new ConnectionSingleton();
        }
        return self::$instance->getConnection();
    }
    
    /**
     * Connects to database automatically.
     *
     * @throws ConnectionException
     */
    private function __construct()
    {
        if (!self::$dataSource) {
            throw new ConnectionException("Datasource not set!");
        }
        $className = str_replace("DataSource", "Driver", get_class(self::$dataSource));
        $this->database_connection = new $className();
        if ($this->database_connection instanceof Server) {
            $this->database_connection->connect(self::$dataSource);
        }
    }
    
    /**
     * Internal utility to get connection.
     *
     * @return Driver
     */
    private function getConnection(): Driver
    {
        return $this->database_connection;
    }
    
    /**
     * Disconnects from database server automatically.
     */
    public function __destruct()
    {
        try {
            if ($this->database_connection && $this->database_connection instanceof Server) {
                $this->database_connection->disconnect();
            }
        } catch (\Exception $e) {
        }
    }
}
