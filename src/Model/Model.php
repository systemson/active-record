<?php

namespace Amber\ActiveRecord\Model;

use ArrayAccess;
use Amber\Config\Config;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use Amber\ActiveRecord\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;
use PDO;
use PDOStatement;

abstract class Model implements ArrayAccess
{
    use SingletonTrait, AccesorTrait, DataHandlerTrait, DataDefinitionTrait;

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

            $this->updateAttributes($array);
        }
    }

    private function primary()
    {
        return $this->pk ?? $this->{$this->primary_key} ?? null;
    }

    private function updateAttributes($array, $pk = null)
    {
        $this->attributes = $array;
        $this->original = $array;
        $this->pk = $pk ?? $array[$this->primary_key];
    }
}
