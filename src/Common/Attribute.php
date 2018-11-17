<?php

namespace Amber\Gemstone\Common;

/**
 *
 */
class Attribute
{
	private $name;
	private $type;
	private $max;
	private $default;
	private $rules;

	private $current;
	private $stored;

	const TYPES = [
		'string',
		'int',
		'bool',
		'date',
	];

	public function __construct(string $name, string $rules = null, $value = null)
	{
		$this->name = $name;
		$this->rules($rules);
		$this->stored = $value;
	}

	public function name()
	{
		return $this->name;
	}

	public function type()
	{
		if (!is_null($this->type)) {
			return $this->type;
		}

		return $this->type = $this->findRule('type');
	}

	public function max()
	{
		if (!is_null($this->max)) {
			return $this->max;
		}

		return $this->max = $this->getArg('max');
	}

	public function default()
	{
		if (!is_null($this->default)) {
			return $this->default;
		}

		return $this->default = $this->getArg('default');
	}

	public function rules(string $rules = null)
	{
		if (is_null($rules)) {
			return $this->rules;
		}

		return $this->rules = explode('|', $rules);
	}

	public function current($current = null)
	{
		if (is_null($current)) {
			return $this->current;
		}

		$this->current = $current;
		return $this;
	}

	public function stored($stored = null)
	{
		if (is_null($stored)) {
			return $this->stored;
		}

		$this->stored = $stored;
		return $this;
	}

	public function value()
	{
		return $this->current ?? $this->stored ?? $this->default();
	}

	public function isValid()
	{
		return $this->validate($this->value(), $this->rules);
	}

	private function findRule(...$name)
	{
		$result = array_uintersect($this->rules(), $name, function ($a, $b) {
			if (strpos($a, $b) !== false) {
    			return false;
			}
			return true;
		});

		$rule = array_values($result);
		return $rule[0] ?? null;
	}

	private function getArg($name)
	{
		$rule = explode('=', $this->findRule($name));

		return $rule[1] ?? null;
	}

	public function clone()
	{
		return clone $this;
	}
}
