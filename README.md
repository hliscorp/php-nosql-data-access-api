# PHPNoSQLAPI

This API is unique in attempting to build something like a PDO on top of all no-sql key-value stores, hiding vendors complexity through an OOP layer. The way NoSQL Data Access API works is roughly analogous to SQL Data Access API, with the noted difference it's entirely built in PHP rather than being just a wrapper over a native C library included by default in PHP (PDO). Because it is entirely built in PHP, it offered me the freedom to create the whole architecture, which consists in an interplay of:

- data sources: encapsulate connection specific data that identify no-sql vendor. Each data source class corresponds to a no-sql vendor and must extend NoSQLDataSource.
- drivers: encapsulate vendor-specific implementations of generic no-sql operations. Each driver class corresponds to a no-sql vendor and must implement NoSQLDriver and NoSQLServer, if vendor requires a server.
- singleton/factory of drivers: encapsulate automatic production and disposal of connections, making sure a single connection is always used for same server and that connection is closed when script ends. This is done by NoSQLConnectionSingleton, to be used when your application uses a single no-sql vendor, and NoSQLConnectionFactory, to be used when your application uses multiple no-sql vendors.

Just like PDO, API does not support all no-sql vendors on the market! It only supports the ones that are by far the most commonly used: APC/APCu, Memcache/Memcached, Redis & Couchbase. No support is provided for MongoDB, HBase or Cassandra because they are hybrid solutions that merge concepts of SQL and NoSQL and do not work like a normal key-value store.

More information here:<br/>
http://www.lucinda-framework.com/nosql-data-access-api
