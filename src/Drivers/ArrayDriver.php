<?php

namespace Amber\Model\Drivers;

use Amber\Collection\CollectionAware\CollectionAwareInterface;
use Amber\Collection\CollectionAware\CollectionAwareTrait;
use Amber\Model\Base\Essentials;
use Amber\Model\Base\Driver\DriverInterface;
use Amber\Model\Base\Model\ModelInterface;

class ArrayDriver extends Essentials implements DriverInterface
{
    public function createDB(string $name): bool
    {
        $this->collection['databases'][$name] = [];

        return true;
    }

    public function dropDB(string $name): bool
    {
        unset($this->collection['databases'][$name]);

        return true;
    }

    public function createTable(ModelInterface $model): bool
    {
        $db_name = $model->getConnection()->db_name;
        $table = $model->getTable();
        $columns = $model->getColumns();

        $this->collection['databases'][$db_name] = [$table => $columns];
        $this->collection['tables'][$db_name . '@' . $table] = [];

        return true;
    }

    public function hasTable(ModelInterface $model): bool
    {
        $db_name = $model->getConnection()->db_name;
        $table = $model->getTable();
        $columns = $model->getColumns();

        return isset($this->collection['databases'][$db_name][$table]) && isset($this->collection['tables'][$db_name . '@' . $table]);
    }

    public function updateTable(ModelInterface $model): bool
    {
        //
    }

    public function dropTable(ModelInterface $model): bool
    {
        $db_name = $model->getConnection()->name;
        $table = $model->getTable();
        unset($this->collection['database'][$db_name][$table]);
        unset($this->collection['tables'][$db_name . '@' . $table]);

        return true;
    }

    public function save(ModelInterface $model): bool
    {
        $db_name = $model->getConnection()->db_name;
        $table = $model->getTable();

        $this->collection['database'][$db_name][$table][] = $model->toArray();

        return true;
    }
}
