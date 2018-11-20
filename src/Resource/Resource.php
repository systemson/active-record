<?php

namespace Amber\Gemstone\Resource;

use Amber\Gemstone\Common\AttributeAwareTrait;
use Amber\Gemstone\Common\AttributeAwareInterface;
use Amber\Gemstone\Common\Validator;

/**
 *
 */
class Resource implements AttributeAwareInterface
{
    use AttributeAwareTrait;

    private $name;
    protected $attributes;
    private $id;
    private $relations;

    public function __construct(string $name, iterable $attributes, $id)
    {
        $this->name = $name;

        $this->attributes = $attributes;
        
        $this->id = $id;
    }

    public function __set($key, $value)
    {
        if ($this->attributes->has($key)) {
            $this->attributes->setValue($key, $value);
        }
    }

    public function __get($key)
    {
        if ($this->attributes->has($key)) {
            return $this->attributes->getValue($key);
        }

        return null;
    }

    public function isValid()
    {
        foreach ($this->attributes as $key => $not_used) {
            $result = Validator::validate($this->{$key}, $this->attributes->getRules($key));
            
            if ($result !== true) {
                //$value = var_export($this->{$key}, true);
                
                //throw new \Exception("Value [{$value}] for attribute ['{$key}'] failed validating rule ['{$result}'].");
                return false;
            }
        }
        return $result;
    }
}
