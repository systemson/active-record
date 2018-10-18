<?php

namespace Amber\Model\Base\Migration;

use Amber\Model\Base\Driver\DriverInterface;
use Amber\Model\Base\Model\ModelInterface;

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

    public function setDriver(DriverInterface $driver)
    {
        return $this->driver = $driver;
    }
}
