<?php

namespace Amber\Gemstone\Common;

/**
 *
 */
trait AttributeAwareTrait
{
    private function initAttributeCollection()
    {
        if (!$this->attributes instanceof AttributeCollection) {
            $this->attributes = new AttributeCollection();
        }
    }

    public function setAttribute(Attribute $attribute)
    {
        $this->initAttributeCollection();

        $this->attributes->set($attribute->name(), $attribute);
    }

    public function getAttribute(string $name)
    {
        $this->initAttributeCollection();

        $this->attributes->get($name);
    }
}
