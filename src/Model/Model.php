<?php

namespace Amber\ActiveRecord\Model;

use ArrayAccess;
use PDO;
use PDOStatement;
use Amber\Config\Config;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use Amber\ActiveRecord\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;
use Amber\ActiveRecord\Database\Database;
use Carbon\Carbon;
use Amber\ActiveRecord\Database\Attribute;
use Amber\ActiveRecord\Database\Entity;

abstract class Model implements ArrayAccess
{
    use SingletonTrait, AccesorTrait, QueriesTrait;

    protected $name;
    protected $primary_key = 'id';
    protected $columns = [];
    protected $dates = true;
    protected $softdelete = false;

    private $pk;
    private $attributes = [];
    private $original = [];

    public function __construct(PDOStatement $stmt = null)
    {
        if (!is_null($stmt)) {
            $array = $stmt->fetch();

            $this->attributes = $array;
            $this->original = $array;
            $this->pk = $array[$this->primary_key] ?? null;
        }
    }

    private function diff()
    {
        return array_diff_assoc($this->attributes, $this->original);
    }

    private function updates()
    {
        foreach ($this->diff() as $key => $value) {
            $ret[] = "{$key} = {$this->value($value)}";
        }

        return implode(', ', $ret);
    }

    private function setTimestamps()
    {
        if ($this->dates) {
            $now = (string) Carbon::now();

            $this->attributes['created_at'] = $now;
            $this->attributes['edited_at'] = $now;
        }
    }

    private function setEditedAt()
    {
        if ($this->dates) {
            $this->attributes['edited_at'] = (string) Carbon::now();
        }
    }

    private function setDeletedAt()
    {
        if ($this->softdelete) {
            $this->attributes['deleted_at'] = (string) Carbon::now();
        }
    }

    private function getAttributesColumns()
    {
        return array_keys($this->attributes);
    }

    private function getAttributesValues()
    {
        return array_map(
            function ($value) {
                return $this->value($value, true);
            },
            $this->attributes
        );
    }

    private function value($value)
    {
        return var_export($value, true);
    }

    private function primary()
    {
        return $this->pk ?? $this->{$this->primary_key};
    }

    private function columns()
    {
        foreach ($this->columns as $name => $options) {
            $array = explode('|', $options);

            $type =  array_shift($array);
            $constraints = $array;

            $attributes[] = $this->handleColum($name, $type, $constraints);
        }

        return $attributes;
    }

    private function handleColum($name, $type, $constraints)
    {
        $column = new Attribute($name, $type);

        return $this->applyConstraints($column, $constraints);
    }

    private function applyConstraints($column, $constraints)
    {
        foreach ($constraints as $value) {
            $array = explode(':', $value);
            $method =  array_shift($array);
            $arg =  $array;

            $column = call_user_func_array([$column, $method], $arg);
        }

        return $column;
    }

    public function sql()
    {
        return (string) (new Entity($this->name, $this->columns()));
    }
}
