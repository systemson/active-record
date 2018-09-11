<?php

namespace Amber\Model\Base\Driver;

trait DriverTrait
{
    protected $table;

    protected $columns;

    protected $connection;

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($config)
    {
        $this->connection = $config;
    }
}
