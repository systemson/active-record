<?php

namespace Amber\Gemstone\Entity;

use Amber\Gemstone\Base\Essential;
use Amber\Gemstone\Database\Database;

/**
 * Abstract layer for handling the database entities.
 */
class Entity extends Essential
{
    public $name;
    public $config;
    public $attributes = [];
    public $original = [];

    const CREATED_AT_NAME = 'created_at';
    const EDITED_AT_NAME = 'edited_at';
    const DELETED_AT_NAME = 'deleted_at';

    public function __construct($name, $attributes = [])
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function init($columns)
    {
        $this->config = [
            'dbname' => $columns[0]['table_catalog'],
            'schema' => $columns[0]['table_schema'],
            'name' => $columns[0]['table_name'],
        ];

        foreach ($columns as $key => $value) {
            $attribute = $this->attribute($value['column_name'], $value['data_type']);

            if (!is_null($size = $value['character_maximum_length'])) {
                $attribute->size($size);
                
            }

            if (!is_null($default = $value['column_default'])) {
                $attribute->default($default);
            }

            if (!$value['is_nullable']) {
                $attribute->notNull();
            }
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function attribute(string $name, string $type): Attribute
    {
        return $this->attributes[] = new Attribute($name, $type);
    }

    public function id(string $name = 'id'): Attribute
    {
        return $this->attribute($name, 'SERIAL')->primary();
    }

    public function timestamps(): void
    {
        $this->date(static::CREATED_AT_NAME);
        $this->date(static::EDITED_AT_NAME);
    }

    public function string(string $name, int $size = null): Attribute
    {
        return $this->attribute($name, 'string')->size($size);
    }

    public function integer(string $name, int $size = null): Attribute
    {
        return $this->attribute($name, 'integer')->size($size);
    }

    public function boolean(string $name): Attribute
    {
        return $this->attribute($name, 'boolean');
    }

    public function date(string $name, $constraint = null): Attribute
    {
        return $this->attribute($name, 'date')->constraint($constraint);
    }

    public function primary($column): void
    {
        $this->attributes[] = "PRIMARY KEY ({$column})";
    }

    public function foreign(string $table, string $column): void
    {
        $this->attributes[] = "FOREIGN KEY (fk_{$table}_{$column}) REFERENCES {$table}({$column})";
    }


    public function constraint($name): void
    {
        $this->attributes[] = strtoupper($name);
    }

    public function getAttributes()
    {
        $attributes = !empty($this->attributes) ?
            '(' . implode(', ', $this->attributes) . ')' :
            null;

        return trim("{$attributes}");
    }

    public function createStmt()
    {
        return trim("CREATE TABLE {$this->name} {$this->getAttributes()}");
    }

    public function createOrReplaceStmt()
    {
        return trim("CREATE OR REPLACE TABLE {$this->name} {$this->getAttributes()}");
    }

    public function info()
    {
        return "SELECT table_catalog, table_schema, table_name, column_name, data_type, character_maximum_length, column_default, is_nullable FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->name()}'";
    }

    public function __toString()
    {
        return $this->name();
    }
}
