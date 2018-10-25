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

class Connector implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait;

    private function getDriver()
    {
        return $this->getConfig('database.driver');
    }

    private function getHost()
    {
        return $this->getConfig('database.host');
    }

    private function getPort()
    {
        return $this->getConfig('database.port');
    }

    private function getDbname()
    {
        return $this->getConfig('database.dbname');
    }

    private function getUser()
    {
        return $this->getConfig('database.user');
    }

    private function getPassword()
    {
        return $this->getConfig('database.password');
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

    private function pdo(): PDO
    {
        return new PDO($this->credentials());
    }

    private function query(string $statement, iterable $args = []): Collection
    {
    	$query = $this->pdo()->prepare($statement);
    	$query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Collection::class);
    	$query->execute($args);

    	return new Collection($query->fetchAll());
    }

    public static function config(array $config)
    {
        self::getInstance()->setConfig($config);
    }
}
