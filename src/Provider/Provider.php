<?php

namespace Amber\Gemstone\Provider;

use Amber\Gemstone\Common\Attribute;
use Amber\Gemstone\Common\AttributeAwareTrait;
use Amber\Gemstone\Common\AttributeAwareInterface;
use Amber\Gemstone\Resource\Resource;

/**
 *
 */
class Provider implements AttributeAwareInterface
{
	use AttributeAwareTrait;

	private $name = 'users';

	protected $attributes = [
		'username' => 'string|unique|max=50|not_null|default=default',
		'password' => 'string|max=254|not_null',
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

	private function initAttributes()
	{
		foreach ($this->attributes as $name => $rules) {
			$this->setAttribute(new Attribute($name, $rules));
		}
	}

	private function updatedAttributes($array)
	{
		$attributes = $this->attributes->clone();

		foreach ($array as $name => $value) {
			if ($attributes->has($name)) {
				$attributes->set($name, new Attribute($name, '', $value));
			} else {
				$attributes->set($name, new Attribute($name, '', $value));
			}
		}

		return $attributes;
	}

	public function new()
	{
		return new Resource($this->name(), $this->attributes()->clone(), $this->id());
	}

	public function find($id)
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
