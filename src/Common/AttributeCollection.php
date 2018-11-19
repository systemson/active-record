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
		if ($this->locked) {
			$this->values['current'][$key];
		} else {
			$this->values['stored'][$key];
		}
	}

	public function lock()
	{
		$this->locked = true;
		return $this;
	}
}
