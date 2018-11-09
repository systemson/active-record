<?php

namespace Amber\Gemstone\Model;

use ArrayAccess;
use Amber\Config\Config;
use Amber\Gemstone\Config\ConfigAwareInterface;
use Amber\Gemstone\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;
use PDO;
use PDOStatement;

/**
 * This class represents the records to be persisted.
 *
 * @todo MUST implement Attribute class for every attribute of the Model.
 */
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
        $this->pk = $array[$this->primary_key] ?? $pk;
    }
}
