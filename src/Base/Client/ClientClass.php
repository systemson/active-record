<?php

namespace Amber\Base\Client;

use Amber\Model\Base\Driver\DriverInterface;
use Amber\Model\Base\Essentials;

abstract class ClientClass extends Essentials
{
    protected $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

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

    /**
     * Calls statically methods from the DriverInterface instance.
     *
     * @param string $method The Cache driver method.
     * @param array $args    The arguments for the Cache driver method.
     *
     * @return mixed
     */
    public static function __callStatic($method, $args = [])
    {
        return call_user_func_array([self::getDriver(), $method], $args);
    }

    /**
     * Calls methods from the DriverInterface instance.
     *
     * @param string $method The Cache driver method.
     * @param array $args    The arguments for the Cache driver method.
     *
     * @return mixed
     */
    public function __call($method, $args = [])
    {
        return call_user_func_array([$this->getDriver(), $method], $args);
    }
}
