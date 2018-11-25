<?php

namespace Amber\Gemstone\Common;

use Amber\Phraser\Phraser;
use Amber\Utils\Implementations\AbstractSingleton;
use Amber\Utils\Traits\SingletonTrait;
use Carbon\Carbon;

/**
 *
 */
class Validator extends AbstractSingleton
{
    private $validations = [
        'string',
        'integer',
        'boolean',
        'date',
        'max',
        'not_null',
    ];

    /**
     * @var array The protected method(s) that should be statically exposed.
     */
    protected static $passthru = [
        'validate',
        'validateString',
        'validateInteger',
        'validateBoolean',
        'validateDate',
        'validateMax',
        'validateNotNull',
    ];

    protected function validate($value, iterable $rules)
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

    protected function validateString($value)
    {
        return is_string($value) && $value !== '';
    }

    protected function validateInteger($value)
    {
        return is_int($value);
    }

    protected function validateBoolean($value)
    {
        if (is_bool($value) || in_array(strtolower($value), ['false', 'true', '1', '0', 1, 0], true)) {
            return true;
        }

        return false;
    }

    protected function validateDate($value, $format)
    {
        $date = Carbon::createFromFormat($format, $value);
        return $date && $date->format($format) === $value;
    }


    /* Rule validations */

    protected function validateMax($value, $max)
    {
        return strlen($value) <= $max;
    }

    protected function validateNotNull($value)
    {
        return !is_null($value);
    }
}
