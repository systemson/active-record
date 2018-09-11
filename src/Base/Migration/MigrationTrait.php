<?php

namespace Amber\Model\Base\Migration;

use Amber\Model\Base\Driver\DriverInterface;
use Amber\Model\Base\Model\ModelInterface;

trait MigrationTrait
{
    protected $driver;

    /*public function getDriver(): DriverInterface
    {
        if (!$this->driver instanceof DriverInterface) {
            $driver = self::DRIVERS[$this->getConfig('default_driver')];
            $this->driver = new $driver();
        }

        return $this->driver;
    }

    public function setDriver(DriverInterface $driver)
    {
        return $this->driver = $driver;
    }

    public function createDB(string $name): bool
    {
        return $this->getDriver()->createDB($name);
    }

    public function dropDB(string $name): bool
    {
        return $this->getDriver()->dropDB($name);
    }

    public function createTable(ModelInterface $model): bool
    {
        return $this->getDriver()->createTable($model);
    }

    public function hasTable(ModelInterface $model): bool
    {
        return $this->getDriver()->hasTable($model);
    }

    public function updateTable(ModelInterface $model): bool
    {
        return $this->getDriver()->updateTable($model);
    }

    public function dropTable(ModelInterface $model): bool
    {
        return $this->getDriver()->dropTable($model);
    }*/
}
