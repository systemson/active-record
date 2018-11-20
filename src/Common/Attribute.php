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
        'int',
        'bool',
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
}
