<?php

namespace Amber\Model\Contracts;

use PDO;

interface EssentialsInterface
{
	public function pdo(): PDO;

    public function toSql(): string;

    public function __toString();
}
