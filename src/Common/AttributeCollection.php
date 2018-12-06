<?php

namespace Amber\Gemstone\Common;

use Amber\Collection\Collection;

/**
 *
 */
class AttributeCollection extends Collection implements AttributeCollectionInterface
{
    private $values;
    private $locked;

    public function setValue($key, $value)
    {
        if ($this->hasNot($key)) {
            $this->set($key, new Attribute($key));
        }

        if ($this->locked) {
            $this->values['current'][$key] = $value;
        } else {
            $this->values['stored'][$key] = $value;
        }
    }

    public function getValue($key)
    {
        $value = $this->values['current'][$key] ?? $this->values['stored'][$key] ?? $this->get($key)->default();
        $type = $this->get($key)->type();

        return $this->parse($value, $type);
    }

    public function getRules($key): iterable
    {
        return $this->get($key)->rules();
    }

    private function parse($value, string $type = null)
    {
        switch ($type) {
            case 'string':
                return $this->parseString($value);
                break;

            case 'integer':
                return $this->parseInteger($value);
                break;

            case 'boolean':
                return $this->parseBoolean($value);
                break;

            case 'date':
                return $this->parseDate($value);
                break;
            
            default:
                return $value;
                break;
        }
        return $value;
    }

    private function parseString($value)
    {
        return (string) $value;
    }

    private function parseInteger($value)
    {
        return (int) $value;
    }

    private function parseBoolean($value)
    {
        return (bool) $value;
    }

    private function parseDate($value)
    {
        return $value;
    }

    public function lock(): AttributeCollectionInterface
    {
        $this->locked = true;
        return $this;
    }
}
