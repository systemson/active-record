<?php

namespace Amber\Gemstone\Common;

use Amber\Collection\Collection;

/**
 *
 */
class AttributeCollection extends Collection implements AttributeCollectionInterface
{

    private $values;
    private $locked;

    public function setValue($key, $value)
    {
        if ($this->hasNot($key)) {
            $this->set($key, new Attribute($key));
        }

        if ($this->locked) {
            $this->values['current'][$key] = $value;
        } else {
            $this->values['stored'][$key] = $value;
        }
    }

    public function getValue($key)
    {
        return $this->values['current'][$key] ?? $this->values['stored'][$key] ?? $this->get($key)->default();
    }

    public function getRules($key): iterable
    {
        return $this->get($key)->rules();
    }

    public function lock(): AttributeCollectionInterface
    {
        $this->locked = true;
        return $this;
    }
}
