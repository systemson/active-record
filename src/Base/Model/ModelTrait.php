<?php

namespace Amber\Model\Base\Model;

trait ModelTrait
{
    protected $table;

    protected $columns;

    protected $connection = [
        'db_name' => 'array_db',
    ];

    public function getConnection()
    {
        return (object) $this->connection;
    }

    public function setConnection($config)
    {
        $this->connection = $config;
    }

    public function getColumns()
    {
        return array_keys($this->connection);
    }
}
