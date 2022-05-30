<?php

namespace Test\Lucinda\NoSQL;

use Lucinda\NoSQL\ConnectionSingleton;
use Lucinda\NoSQL\Vendor\Redis\DataSource;
use Lucinda\UnitTest\Result;

class ConnectionSingletonTest
{
    public function setDataSource()
    {
        $dataSource = new DataSource(\simplexml_load_string('
        <server driver="redis" host="127.0.0.1"/>
        '));
        ConnectionSingleton::setDataSource($dataSource);
        return new Result(true);
    }


    public function getInstance()
    {
        $connection = ConnectionSingleton::getInstance();
        return new Result($connection->getDriver() instanceof \Redis);
    }
}
