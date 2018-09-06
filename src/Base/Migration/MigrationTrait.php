<?php

namespace Amber\Model\Base\Migration;

trait MigrationTrait
{
    protected $database;

    protected $table;

    protected $columns = [];

    protected $primarykey;

    protected $foreingkeys = [];

    protected $driver;

    public function getDriver(): DriverInterface
    {
        if (!$this->driver instanceof DriverInterface) {
            $driver = self::DRIVERS[$this->getConfig('default_driver')];
            $this->driver = new $driver();
        }

        return $this->driver;
    }

    public function setDriver(string $driver)
    {
        return $this->setConfig(['default_driver' => $driver]);
    }

    protected function getTable(string $table = null)
    {
        return $table ?? $this->table;
    }

    public function create($name, $callable)
    {
        $this->getDriver()->createTable($name, $callable);
    }

    public function update($name, $callable)
    {
        $this->getDriver()->createTable($name, $callable);
    }

    public function drop($name)
    {
        $this->getDriver()->dropTable($name);
    }
}
