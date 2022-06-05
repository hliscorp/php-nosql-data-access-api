<?php

namespace Test\Lucinda\NoSQL\Vendor\Redis;

use Lucinda\NoSQL\Vendor\Redis\DataSource;
use Lucinda\NoSQL\Vendor\Redis\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    public function getDriver()
    {
        $object = new DataSource(
            \simplexml_load_string(
                '
        <server driver="redis" host="127.0.0.1"/>
        '
            )
        );
        return new Result($object->getDriver() instanceof Driver);
    }
}
