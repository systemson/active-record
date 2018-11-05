<?php

namespace Amber\ActiveRecord\Database;

use PDO;
use PDOStatement;
use Amber\Config\Config;
use Amber\Collection\Collection;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use Amber\ActiveRecord\Config\ConfigAwareTrait;
use Amber\Utils\Implementations\AbstractSingleton;
use Amber\Utils\Traits\SingletonTrait;
use Amber\Utils\Traits\BaseFactoryTrait;

/**
 * PDO Factory class.
 *
 * @todo NEEDS refactoring.
 * @todo Should implement Data Mapper pattern.
 */
class Database implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait, BaseFactoryTrait;

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

    private function credentials(): array
    {
        $configs = $this->getConfig('database');
        $configs = array_diff_key($configs, array_flip(['driver', 'user', 'password']));

        $credentials = [];

        foreach ($configs as $key => $value) {
            $credentials[] = "{$key}={$value}";
        }

        return [
            $this->getDriver() . ':' . implode(';', $credentials),
            $this->getUser(),
            $this->getPassword()
        ];
    }

    private function pdo(): PDO
    {
        $pdo = $this->make(AmberPDO::class, $this->credentials());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        return $pdo;
    }

    private function run(string $statement, iterable $args = []): bool
    {
        return $this->pdo()->prepare($statement)->execute($args);
    }

    private function getAll(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $query = $this->pdo()->prepare($statement);
        $query->setFetchMode(PDO::FETCH_CLASS, $class);
        $query->execute($args);

        return $query->fetchAll();
    }

    private function get(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $stmt = $this->pdo()->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return new $class($stmt);
    }

    private function getArray(string $statement, iterable $args = [])
    {
        $stmt = $this->pdo()->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return $stmt->fetch();
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

    public static function model($class, callable $closure)
    {
        $table = (new $class())->table();

        $closure($table);

        return true;
    }
}
