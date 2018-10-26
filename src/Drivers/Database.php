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

    public function create()
    {
        return (Connector::pdo()->prepare("CREATE DATABASE {$this->name}"))->execute();
    }

    public function createOrReplace()
    {
        return (Connector::pdo()->prepare("CREATE OR REPLACE DATABASE {$this->name}"))->execute();
    }

    public function drop()
    {
        return (Connector::pdo()->prepare("DROP DATABASE {$this->name}"))->execute();
    }

    public function dropIfExists()
    {
        return (Connector::pdo()->prepare("DROP DATABASE IF EXISTS {$this->name}"))->execute();
    }
}