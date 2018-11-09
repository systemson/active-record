<?php

namespace Amber\Gemstone\Contracts;

use PDO;

interface EssentialsInterface
{
    public function pdo(): PDO;

    public function toSql(): string;

    public function __toString();
}
