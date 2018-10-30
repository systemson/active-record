<?php

namespace Amber\ActiveRecord\Model;

use Amber\ActiveRecord\Database\Database;
use Amber\ActiveRecord\Database\Entity;
use Amber\ActiveRecord\Database\Attribute;
use PDOStatement;

/** 
 */
trait DataDefinitionTrait
{
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

    public function table(): Entity
    {
        return new Entity($this->name, $this->columns());
    }
}
