<?php

namespace Amber\Gemstone\Database;

use Amber\Gemstone\Base\Essential;
use Amber\Collection\Collection;

/**
 *
 */
trait Query
{
    public $entity;
    public $args;

    public function __construct($entity, $args)
    {
        $this->entity = $entity;
        $this->args = new Collection();
    }

    public function select(...$columns): self
    {
        foreach ($columns as $column) {
            $this->args['select'][$column] = $column;
        }

        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->args['where'][$column] = [$operator, $this->getSqlValue($value)];

        return $this;
    }

    public function orderBy(...$columns): self
    {
        foreach ($columns as $column) {
            $this->args['order'][$column] = $column;
        }

        return $this;
    }

    private function getSqlValue($value): string
    {
        return var_export($value, true);
    }

    private function getVars($name) {
        $return = [];

        foreach ($this->args[$name] as $column => $value) {
            $return[] = ":{$name}_{$column}";
        }

        return implode(', ', $return);
    }

    public function toSql(): string
    {
        if (isset($this->args['select'])) {
            $select = implode(', ', array_keys($this->args['select']));
        } else {
            $select = '*';
        }

        $statement = "SELECT {$this->getVars('select')} FROM {$this->entity}";

        /*if (isset($this->args['where'])) {
            foreach (array_keys($this->args['where']) as $var => $options) {
                $where[] = "{options[0] $var}"
            }
            
            $statement .= " WHERE {$where}";
        }

        if (isset($this->args['order'])) {
        }*/

        return $statement;
    }
}
