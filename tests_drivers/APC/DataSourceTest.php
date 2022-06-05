<?php

namespace Test\Lucinda\NoSQL\Vendor\APC;

use Lucinda\NoSQL\Vendor\APC\DataSource;
use Lucinda\NoSQL\Vendor\APC\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    public function getDriver()
    {
        $dataSource = new DataSource();
        return new Result($dataSource->getDriver() instanceof Driver);
    }
}
