<?php

namespace Amber\Gemstone\Common;

use Amber\Collection\Collection;

/**
 *
 */
class AttributeCollection extends Collection
{

	private $values;
	private $locked;

	public function setValue($key, $value)
	{
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

	public function attribute($name)
	{
		return $this->get($name);
	}

	public function lock()
	{
		$this->locked = true;
		return $this;
	}
}
