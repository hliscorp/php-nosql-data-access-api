<?php

namespace Test\Lucinda\NoSQL\Vendor\Redis;

use Lucinda\NoSQL\ConnectionException;
use Lucinda\UnitTest\Result;

class DriverTest
{
    private $object;
    private $dataSource;

    public function __construct()
    {
        $this->dataSource = new \Lucinda\NoSQL\Vendor\Redis\DataSource(
            \simplexml_load_string(
                '
            <server driver="redis" host="127.0.0.1"/>
        '
            )
        );
        $this->object = $this->dataSource->getDriver();
    }

    public function connect()
    {
        try {
            $this->object->connect($this->dataSource);
            return new Result(true, "connection success");
        } catch (ConnectionException $e) {
            return new Result(false, "connection success");
        }
    }

    public function set()
    {
        $this->object->set("unit_test", 1);
        return new Result(true);
    }


    public function contains()
    {
        return new Result($this->object->contains("unit_test"));
    }


    public function get()
    {
        return new Result($this->object->get("unit_test")==1);
    }


    public function increment()
    {
        return new Result($this->object->increment("unit_test")==2);
    }


    public function decrement()
    {
        return new Result($this->object->decrement("unit_test")==1);
    }


    public function delete()
    {
        $this->object->delete("unit_test");
        return new Result(!$this->object->contains("unit_test"));
    }


    public function flush()
    {
        $this->object->set("unit_test", 1);
        $this->object->flush();
        return new Result(!$this->object->contains("unit_test"));
    }


    public function getDriver()
    {
        return new Result($this->object->getDriver() instanceof \Redis);
    }


    public function disconnect()
    {
        $this->object->disconnect();
        return new Result(true);
    }
}
