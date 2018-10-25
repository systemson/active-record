<?php

namespace Amber\Model\Drivers;

use PDO;
use Amber\Config\Config;
use Amber\Model\Contracts\ConnectorInterface;
use Amber\Collection\Collection;
use Amber\Model\Config\ConfigAwareInterface;
use Amber\Model\Config\ConfigAwareTrait;
use Amber\Utils\Implementations\AbstractSingleton;
use Amber\Utils\Traits\SingletonTrait;

class DB implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait;

    private $pdo;

    private function getDriver()
    {
        return $this->getConfig('database.driver');
    }

    private function getHost()
    {
        return $this->getConfig('host');
    }

    private function getPort()
    {
        return $this->getConfig('port');
    }

    private function getDbname()
    {
        return $this->getConfig('dbname');
    }

    private function getUser()
    {
        return $this->getConfig('user');
    }

    private function getPassword()
    {
        return $this->getConfig('password');
    }

    private function credentials(): string
    {
        $configs = $this->getConfig('database');
        unset($configs['driver']);

        $credentials = [];

        foreach ($configs as $key => $value) {
            $credentials[] = "{$key}={$value}";
        }

        return $this->getDriver() . ':' . implode(';', $credentials);
    }

    private function connect(): PDO
    {
        return new PDO($this->credentials());
    }

    private function query(string $statement, iterable $args = []): Collection
    {
    	$query = $this->connect()->prepare($statement);
    	$query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Collection::class);
    	$query->execute($args);

    	return new Collection($query->fetchAll());
    }

    public static function config(array $config)
    {
        self::getInstance()->setConfig($config);
    }
}
