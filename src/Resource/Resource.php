<?php

namespace Amber\Gemstone\Resource;

use Amber\Gemstone\Common\AttributeAwareTrait;
use Amber\Gemstone\Common\AttributeAwareInterface;
use Amber\Gemstone\Common\Validator;
use Amber\Gemstone\Common\AttributeCollection;

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

    public function __construct(string $name, AttributeCollection $attributes, $id)
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
        if (empty($this->attributes)) {
            return true;
        }

        foreach ($this->attributes as $key => $not_used) {
            $result = Validator::validate($this->{$key}, $this->attributes->getRules($key));
            
            if ($result !== true) {
                //$value = var_export($this->{$key}, true);
                
                /*throw new \Exception(
                    "Faild validating attribute ['{$key}'] for rule ['{$result}'] with value [{$value}].");*/
                return false;
            }
        }

        return $result;
    }

    public function toArray(): array
    {
        $return = [];

        foreach ($this->attributes as $name => $attribute) {
            $result[$name] =  $this->attributes->getValue($name);
        }

        return $result;
    }
}
