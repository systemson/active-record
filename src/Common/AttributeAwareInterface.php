<?php

namespace Amber\Gemstone\Common;

use Amber\Collection\Collection;

/**
 *
 */
interface AttributeAwareInterface
{
	public function setAttribute(Attribute $attribute);

	public function getAttribute(string $name);
}
