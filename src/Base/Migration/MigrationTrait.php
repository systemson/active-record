<?php

namespace Amber\Model\Base\Migration;

use Amber\Model\Base\Driver\DriverInterface;

trait MigrationTrait
{
    protected $driver;

    public function getDriver(): DriverInterface
    {
        if (!$this->driver instanceof DriverInterface) {
            $driver = self::DRIVERS[$this->getConfig('default_driver')];
            $this->driver = new $driver();
        }

        return $this->driver;
    }

    public function setDriver($driver)
    {
        return $this->setConfig(['default_driver' => $driver]);
    }

    public function createTable($model): bool
    {
        return $this->getDriver()->createTable($model);
    }

    public function hasTable($model): bool
    {
        return $this->getDriver()->hasTable($model);
    }

    public function updateTable($model): bool
    {
        return $this->getDriver()->updateTable($model);
    }

    public function dropTable($model): bool
    {
        return $this->getDriver()->dropTable($model);
    }
}
