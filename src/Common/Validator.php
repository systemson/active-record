<?php

namespace Amber\Gemstone\Common;

use Amber\Phraser\Phraser;
use Amber\Utils\Traits\SingletonTrait;
use Carbon\Carbon;

/**
 *
 */
class Validator
{
    use SingletonTrait;

    private $validations = [
        'string',
        'integer',
        'boolean',
        'date',
        'max',
        'not_null',
    ];

    private function validate($value, iterable $rules)
    {
        $nullable = !in_array('not_null', $rules);

        foreach ($rules as $rule) {
            if ($nullable && is_null($value)) {
                break;
            }

            $name = $this->getRuleName($rule);
            $args =  $this->getRuleArgs($rule);

            if (in_array($name, $this->validations)) {
                $valid = $this->validateRule($value, $name, $args);

                if (!$valid) {
                    return $rule;
                }
            }
        }

        return true;
    }

    private function getRuleType($rules)
    {
        $index = array_search($this->types, $rules);

        return $this->getRuleName($rules[$index] ?? null);
    }

    private function validateRule($value, $rule, $args)
    {
        return call_user_func_array(
            [$this, $this->getValidatorMethod($rule)],
            [$value, $args]
        );
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


    /* Type validations */

    private function validateString($value)
    {
        return is_string($value) && $value !== '';
    }

    private function validateInteger($value)
    {
        return is_int($value);
    }

    private function validateBoolean($value)
    {
        if (is_bool($value) || in_array(strtolower($value), ['false', 'true', '1', '0', 1, 0], true)) {
            return true;
        }

        return false;
    }

    private function validateDate($value, $format)
    {
        $date = Carbon::createFromFormat($format, $value);
        return $date && $date->format($format) == $value;
    }


    /* Rule validations */

    private function validateMax($value, $max)
    {
        return strlen($value) <= $max;
    }

    private function validateNotNull($value)
    {
        return !is_null($value);
    }
}
