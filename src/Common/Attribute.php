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

    const TYPES = [
        'string',
        'integer',
        'boolean',
        'date',
    ];

    public function __construct(string $name, string $rules = null)
    {
        $this->name = $name;
        $this->rules($rules);
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

        return $this->type = $this->findRule(static::TYPES);
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

    private function findRule($name)
    {
        if (empty($this->rules())) {
            return null;
        }

        $result = array_filter($this->rules(), function ($rule) use ($name) {
            return strpos($rule, $name) !== false;
        });

        $rule = array_values($result);

        return $rule[0] ?? null;
    }

    private function getName($name)
    {
        $rule = explode('=', $this->findRule($name));

        return $rule[0] ?? null;
    }

    private function getArg($name)
    {
        $rule = explode('=', $this->findRule($name));

        return $rule[1] ?? null;
    }
}
