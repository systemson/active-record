<?php

namespace Amber\ActiveRecord\Model;

use Exception;

trait AccesorTrait
{
    public function offsetSet($offset, $valor) {

        if (is_null($offset)) {
        	throw new Exception('Model attributes must contain a valid name;');
        }

        $this->contenedor[$offset] = $valor;
    }

    public function offsetExists($offset) {
        return isset($this->attributes[$offset]);
    }

    public function offsetUnset($offset) {
        throw new Exception('Model attributes can not be deleted');
    }

    public function offsetGet($offset) {
        return $this->attributes[$offset] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
}
