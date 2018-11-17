<?php

namespace Amber\Gemstone\Common;

use Amber\Collection\Collection;

/**
 *
 */
trait AttributeAwareTrait
{
	private function initAttributeCollection()
	{
		if (!$this->attributes instanceof Collection) {
			$this->attributes = new Collection();
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
