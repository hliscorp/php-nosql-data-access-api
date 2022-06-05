<?php

namespace Test\Lucinda\NoSQL\Vendor\Memcache;

use Lucinda\NoSQL\Vendor\Memcache\DataSource;
use Lucinda\NoSQL\Vendor\Memcache\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    public function getDriver()
    {
        $object = new DataSource(
            \simplexml_load_string(
                '
        <server driver="memcache" host="127.0.0.1"/>
        '
            )
        );
        return new Result($object->getDriver() instanceof Driver);
    }
}
