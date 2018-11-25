<?php

namespace Amber\Gemstone\Provider;

use Amber\Gemstone\Resource\Resource;
use Amber\Gemstone\Common\AttributeCollection;

/**
 *
 */
trait DataHandlerTrait
{
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
}
