<?php

namespace Amber\Model\Drivers;

use PDO;
use Amber\Config\Config;
use Amber\Model\Config\ConfigAwareInterface;
use Amber\Model\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;

class Model implements ConfigAwareInterface
{
    use ConfigAwareTrait, SingletonTrait;

    protected $name = 'db.clientes';
    protected $primary_key = 'codigo';
    private $select = [];
    private $arguments = [];

    private function find($id)
    {
        $this->where($this->primary_key, '=',$id);
        return $this->get();
    }

    private function select(...$columns)
    {
    	foreach ($columns as $column) {
        	$this->select[] = $column;
    	}
    }

    private function where(string $column, string $condition = '=', $value)
    {
        $this->arguments['where'][] = [
        	'column' => $column,
        	'condition' => $condition,
        	'value' => $value,
        	'variable' => ":where_{$column}",
        	'sql' => "{$column} {$condition} :where_{$column}",
        ];

        return $this;
    }

    private function toSql()
    {
        $sql = "SELECT %s FROM {$this->name}";

        if (!empty($this->select)) {
            $select = implode(', ', $this->select);
        } else {
            $select = '*';
        }

        $sql = sprintf($sql, $select);

        if (isset($this->arguments['where'])) {
            $where = array_column($this->arguments['where'], 'sql');
            $where = implode(' AND ', $where);

            $sql .= " WHERE {$where}";

        }

        return $sql;
    }

    private function get()
    {
        return DB::query(
        	$this->toSql(),
        	array_combine(
        		array_column($this->arguments['where'], 'variable'),
        		array_column($this->arguments['where'], 'value')
        	)
        );
    }
}
