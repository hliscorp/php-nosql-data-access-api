<?php

namespace Test\Lucinda\NoSQL;

use Lucinda\NoSQL\ConnectionFactory;
use Lucinda\NoSQL\Vendor\Redis\DataSource;
use Lucinda\UnitTest\Result;

class ConnectionFactoryTest
{
    public function setDataSource()
    {
        $dataSource = new DataSource(
            \simplexml_load_string(
                '
        <server driver="redis" host="127.0.0.1"/>
        '
            )
        );
        ConnectionFactory::setDataSource("local", $dataSource);
        return new Result(true);
    }


    public function getInstance()
    {
        $connection = ConnectionFactory::getInstance("local");
        return new Result($connection->getDriver() instanceof \Redis);
    }
}
