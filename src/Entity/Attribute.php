<?php

namespace Amber\Gemstone\Entity;

use PDO;
use Amber\Config\Config;
use Amber\Gemstone\Config\ConfigAwareInterface;
use Amber\Gemstone\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;

/**
 * Abstract layer for handling the database attributes.
 *
 * @todo MUST validate values on setting and/or getting.
 */
class Attribute implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait;

    private $name;
    private $type;
    private $value;
    private $size;
    private $constraints = [];

    const TYPES = [
        'integer' => 'INT',
        'string' => 'VARCHAR',
        'date' => 'DATE',
        'boolean' => 'BOOLEAN',
    ];

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = static::TYPES[$type] ?? $type;
    }

    public function size(int $size = null)
    {
        $this->size = $size;
        return $this;
    }

    public function autoincrement()
    {
        $this->constraints[] = 'AUTO_INCREMENT';
        return $this;
    }

    public function notNull()
    {
        $this->constraints[] = 'NOT NULL';
        return $this;
    }

    public function unique()
    {
        $this->constraints[] = 'UNIQUE';
        return $this;
    }

    public function primary()
    {
        $this->constraints[] = 'PRIMARY KEY';
        return $this;
    }

    public function default($default)
    {
        $default = var_export($default, true);

        $this->constraints[] = "DEFAULT({$default})";
        return $this;
    }

    public function constraint(string $name = null)
    {
        if (!is_null($name)) {
            $this->constraints[] = strtoupper($name);
        }

        return $this;
    }

    private function getName()
    {
        return "{$this->name}";
    }

    private function getType()
    {
        return "{$this->type}";
    }

    private function getSize()
    {
        return $this->size ? "({$this->size})" : null;
    }

    private function getConstraints()
    {
        return implode(' ', $this->constraints);
    }

    private function toSQL()
    {
        return trim("{$this->getName()} {$this->getType()}{$this->getSize()} {$this->getConstraints()}");
    }

    public function __toString()
    {
        return $this->toSql();
    }
}
