<?php

namespace Test\Lucinda\NoSQL\Vendor\Couchbase;

use Lucinda\NoSQL\Vendor\Couchbase\DataSource;
use Lucinda\NoSQL\Vendor\Couchbase\Driver;
use Lucinda\UnitTest\Result;

class DataSourceTest
{
    private $object;

    public function __construct()
    {
        $this->object = new DataSource(
            \simplexml_load_string(
                '
        <server driver="couchbase" host="127.0.0.1" username="test" password="me" bucket_name="test_bucket" bucket_password="test_password"/>
        '
            )
        );
    }

    public function getHost()
    {
        return new Result($this->object->getHost()=="127.0.0.1");
    }


    public function getUserName()
    {
        return new Result($this->object->getUserName()=="test");
    }


    public function getPassword()
    {
        return new Result($this->object->getPassword()=="me");
    }


    public function getBucketName()
    {
        return new Result($this->object->getBucketName()=="test_bucket");
    }


    public function getBucketPassword()
    {
        return new Result($this->object->getBucketPassword()=="test_password");
    }


    public function getDriver()
    {
        return new Result($this->object->getDriver() instanceof Driver);
    }
}
