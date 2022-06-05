<?php

namespace Lucinda\NoSQL;

/**
 * Implements a data source.
 */
interface DataSource
{
    /**
     * Gets driver associated to data source
     *
     * @return Driver
     */
    public function getDriver(): Driver;
}
