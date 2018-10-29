<?php

namespace Amber\ActiveRecord\Database;

use PDO;
use Amber\Config\Config;
use Amber\Collection\Collection;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use Amber\ActiveRecord\Config\ConfigAwareTrait;
use Amber\Utils\Implementations\AbstractSingleton;
use Amber\Utils\Traits\SingletonTrait;

class Database implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait;

    private function getDriver(): string
    {
        return $this->getConfig('database.driver');
    }

    private function getHost(): string
    {
        return $this->getConfig('database.host');
    }

    private function getPort(): string
    {
        return $this->getConfig('database.port');
    }

    private function getDbname(): string
    {
        return $this->getConfig('database.dbname');
    }

    private function getUser(): string
    {
        return $this->getConfig('database.user');
    }

    private function getPassword(): string
    {
        return $this->getConfig('database.password');
    }

    private function credentials(): string
    {
        $configs = $this->getConfig('database');
        unset($configs['driver']);
        unset($configs['user']);
        unset($configs['password']);

        $credentials = [];

        foreach ($configs as $key => $value) {
            $credentials[] = "{$key}={$value}";
        }

        return $this->getDriver() . ':' . implode(';', $credentials);
    }

    private function pdo(): PDO
    {
        $pdo = new PDO($this->credentials(), $this->getUser(), $this->getPassword());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        return $pdo;
    }

    private function getAll(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $query = $this->pdo()->prepare($statement);
        $query->setFetchMode(PDO::FETCH_CLASS, $class);
        $query->execute($args);

        return $query->fetch();
    }

    private function get(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $stmt = $this->pdo()->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return new $class($stmt);
    }

    public static function config(array $config)
    {
        self::getInstance()->setConfig($config);
    }

    public static function table(string $name, callable $closure)
    {
        $table = new Entity($name);

        $closure($table);

        return true;
    }
}
