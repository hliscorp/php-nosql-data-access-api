<?php
namespace Test\Lucinda\NoSQL\Vendor\APC;

use Lucinda\NoSQL\Vendor\APC\DataSource;
use Lucinda\UnitTest\Result;

class DriverTest
{
    private $object;

    public function __construct()
    {
        $this->object = (new DataSource())->getDriver();
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
}
