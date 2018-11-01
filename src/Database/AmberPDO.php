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
 * @todo SHOULD extend PDO.
 */
class AmberPDO extends PDO
{
    public function run(string $statement, iterable $args = []): bool
    {
        return $this->prepare($statement)->execute($args);
    }

    public function getAll(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $query = $this->prepare($statement);
        $query->setFetchMode(PDO::FETCH_CLASS, $class);
        $query->execute($args);

        return $query->fetchAll();
    }

    public function get(string $statement, iterable $args = [], string $class = 'stdClass')
    {
        $stmt = $this->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return new $class($stmt);
    }

    public function getArray(string $statement, iterable $args = [])
    {
        $stmt = $this->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return $stmt->fetch();
    }
}
