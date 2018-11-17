<?php

namespace Amber\Gemstone\Database;

use PDO;
use stdClass;

/**
 *
 */
class Connector extends PDO
{
    public function run(string $statement, iterable $args = []): bool
    {
        return $this->prepare($statement)->execute($args);
    }

    public function fetchClass(string $statement, iterable $args = [], string $class = stdClass::class)
    {
        $stmt = $this->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return new $class($stmt);
    }

    public function fetchArray(string $statement, iterable $args = [])
    {
        $stmt = $this->prepare($statement);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($args);

        return $stmt->fetch();
    }
}
