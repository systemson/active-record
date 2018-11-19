<?php

namespace Amber\Gemstone\Resource;

use Amber\Gemstone\Common\AttributeAwareTrait;
use Amber\Gemstone\Common\AttributeAwareInterface;

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

}
