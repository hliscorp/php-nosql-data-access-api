<?php

namespace Test\Lucinda\NoSQL\Vendor\Memcached;

use Lucinda\NoSQL\Vendor\Memcached\DataSource;
use Lucinda\NoSQL\Vendor\Memcached\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    public function getDriver()
    {
        $object = new DataSource(
            \simplexml_load_string(
                '
        <server driver="memcached" host="127.0.0.1"/>
        '
            )
        );
        return new Result($object->getDriver() instanceof Driver);
    }
}
