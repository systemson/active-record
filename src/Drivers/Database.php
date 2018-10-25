<?php

namespace Amber\Model\Drivers;

use Amber\Config\Config;
use Amber\Model\Config\ConfigAwareInterface;
use Amber\Model\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;
use Amber\Model\Drivers\Connector;

class Database implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    private $name;

    public function __construct(string $name)
    {
    	$this->name = $name;
    }

    public function exists(): bool
    {
    	$stmt = Connector::pdo()->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->name}'");

		return (bool) $stmt->fetchColumn();
    }

    public function create(bool $override = false)
    {
    	Connector::pdo()->exec("CREATE DATABASE {$this->name}");
    }

    public function drop()
    {
    	Connector::pdo()->exec("DROP DATABASE {$this->name}");
    }
}