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
    private $arguments = [];


    private function find($id)
    {
        $this->where($this->primary_key, $id);
        return $this->get();
    }

    private function where(string $column, $value)
    {
        $this->arguments['where'] = [$column => $value];
    }

    private function toSql()
    {
        $sql = "SELECT %s FROM {$this->name}";

        if (isset($this->arguments['select'])) {
            $select = implode(', ', $this->arguments['select']);
        } else {
            $select = '*';
        }

        $sql = sprintf($sql, $select);

        if (isset($this->arguments['where'])) {
            $where = '';

            foreach ($this->arguments['where'] as $column => $value) {
                $where .= "{$column} = :where_{$column}";
            }

            $sql .= " WHERE {$where}";
        }

        return $sql;
    }

    private function get()
    {
        return DB::query($this->toSql(), $this->arguments);
    }
}
