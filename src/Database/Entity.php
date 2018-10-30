<?php

namespace Amber\ActiveRecord\Database;

use PDO;
use Amber\Config\Config;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use Amber\ActiveRecord\Config\ConfigAwareTrait;

/**
 * Abstract layer for handling the database entities.
 */
class Entity implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    public $name;
    public $attributes = [];

    const CREATED_AT_NAME = 'created_at';
    const EDITED_AT_NAME = 'edited_at';

    public function __construct($name, $attributes = [])
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function attribute(string $name, string $type)
    {
        return $this->attributes[] = new Attribute($name, $type);
    }

    public function id(string $name = 'id')
    {
        return $this->attribute($name, 'SERIAL')->primary();
    }

    public function timestamps()
    {
        $this->date(static::CREATED_AT_NAME);
        $this->date(static::EDITED_AT_NAME);
    }

    public function string(string $name, int $size = null)
    {
        return $this->attribute($name, 'string')->size($size);
    }

    public function integer(string $name, int $size = null)
    {
        return $this->attribute($name, 'integer')->size($size);
    }

    public function boolean(string $name)
    {
        return $this->attribute($name, 'boolean');
    }

    public function date(string $name, $constraint = null)
    {
        return $this->attribute($name, 'date')->constraint($constraint);
    }

    public function primary($column)
    {
        $this->attributes[] = "PRIMARY KEY ({$column})";
    }

    public function foreign(string $table, string $column)
    {
        $this->attributes[] = "FOREIGN KEY (fk_{$table}_{$column}) REFERENCES {$table}({$column})";
    }


    public function constraint($name)
    {
        $this->attributes[] = strtoupper($name);
    }

    private function toSql()
    {
        $attributes = !empty($this->attributes) ?
            '(' . implode(', ', $this->attributes) . ')' :
            null;

        return trim("{$this->name} {$attributes}");
    }

    public function createStmt()
    {
        return trim("CREATE TABLE {$this->toSql()}");
    }

    public function create()
    {
        (Database::pdo()->prepare($this->createStmt()))->execute();
    }

    public function createOrReplace()
    {
        Database::pdo()->query("CREATE OR REPLACE TABLE {$this->toSql()};");
    }

    public function createIfNotExists()
    {
        Database::pdo()->query("CREATE TABLE IF NOT EXISTS {$this->toSql()};");
    }

    public function drop()
    {
        (Database::pdo()->prepare("DROP TABLE {$this->name};"))->execute();
    }

    public function dropIfExists()
    {
        (Database::pdo()->prepare("DROP TABLE IF EXISTS {$this->name};"))->execute();
    }

    public function __toString()
    {
        return $this->createStmt();
    }
}
