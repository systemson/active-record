<?php

namespace Amber\Gemstone\Common;

use Amber\Phraser\Phraser;
use Amber\Utils\Traits\SingletonTrait;
/**
 *
 */
class Validator
{
    use SingletonTrait;

    private $validations = [
        'int',
        'boolean',
        'string',
        'date',
        'max',
        'not_null',
    ];

    private function validate($value, iterable $rules)
    {
        foreach ($rules as $rule) {
            if (in_array($this->getRuleName($rule), $this->validations)) {
                $valid = call_user_func_array(
                    [$this, $this->getValidatorMethod($rule)],
                    [$value, $this->getRuleArgs($rule)]
                );

                if (!$valid) {
                    return $rule;
                }
            }
        }

        return true;
    }

    private function getRuleName($rule)
    {
        $name = explode('=', $rule);

        return $name[0];
    }

    private function getValidatorMethod($rule)
    {
        $name = $this->getRuleName($rule);

        $camel_case = Phraser::fromSnakeCase($name)->toCamelCase();

        return 'validate' . $camel_case;
    }

    private function getRuleArgs($rule)
    {
        $name = explode('=', $rule);

        return $name[1] ?? null;
    }

    private function validateString($value)
    {
        return is_string($value) && $value !== '';
    }

    private function validateInt($value)
    {
        return is_int($value);
    }

    private function validateBoolean($value)
    {
        if (is_bool($value) || in_array(strtolower($value), ['false', 'true', 1, 0])) {
            return true;
        }

        return false;
    }

    private function validateMax($value, $max)
    {
        return strlen($value) <= $max;
    }

    private function validateNotNull($value)
    {
        return !is_null($value);
    }

    private function validateDate($value, $format)
    {
        if ($value instanceof \DateTime) {
            return true;
        } elseif (false) {
        }

        return false;
    }
}
