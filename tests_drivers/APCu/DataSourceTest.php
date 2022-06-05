<?php

namespace Test\Lucinda\NoSQL\Vendor\APCu;

use Lucinda\NoSQL\Vendor\APCu\DataSource;
use Lucinda\NoSQL\Vendor\APCu\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    public function getDriver()
    {
        $dataSource = new DataSource();
        return new Result($dataSource->getDriver() instanceof Driver);
    }
}
