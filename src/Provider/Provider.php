<?php

namespace Amber\Gemstone\Provider;

use Amber\Gemstone\Common\Attribute;
use Amber\Gemstone\Common\AttributeAwareTrait;
use Amber\Gemstone\Common\AttributeAwareInterface;
use Amber\Gemstone\Resource\Resource;
use Amber\Gemstone\Common\AttributeCollection;

/**
 *
 */
class Provider implements AttributeAwareInterface
{
    use AttributeAwareTrait;

    private $name = 'users';

    protected $attributes = [
        'username' => 'string|unique|default=default|max=50|not_null',
        'password' => 'string|max=254|not_null',
        'status' => 'boolean|default=1|not_null',
        'created_at' => 'date=Y-m-d|default=2018-11-21|not_null',
        'edited_at' => 'date=Y-m-d',
    ];

    private $id = 'id';
    private $relations;
    private $mediator;

    public function __construct($mediator)
    {
        $this->mediator = $mediator;

        $this->initAttributes();
    }

    private function attributes()
    {
        return $this->attributes;
    }

    private function initAttributes(): void
    {
        if (empty($this->attributes)) {
            $this->initAttributeCollection();
        }

        foreach ($this->attributes as $name => $rules) {
            $this->setAttribute(new Attribute($name, $rules));
        }
    }

    private function updatedAttributes($array)
    {
        $attributes = $this->attributes->clone();

        foreach ($array as $name => $value) {
            if ($attributes->hasNot($name)) {
                $attributes->set($name, new Attribute($name));
            }
            $attributes->setValue($name, $value);
        }

        return $attributes->lock();
    }

    public function new(): Resource
    {
        return new Resource($this->name(), $this->attributes()->clone()->lock(), $this->id());
    }

    public function find($id): Resource
    {
        $item = $this->mediator->first([$this->id()->name, $id]);

        $attributes = $this->updatedAttributes($item);

        return new Resource($this->name(), $attributes, $this->id($id));
    }

    public function name(): string
    {
        return $this->name;
    }

    public function id($id = null)
    {
        return (object) [
            'name' => $this->id,
            'value' => $id,
        ];
    }
}
