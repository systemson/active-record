<?php

namespace Amber\Model\Base\Migration;

use Amber\Model\Base\Essentials;

class MigrationClass extends Essentials implements MigrationInterface
{
    use MigrationTrait;

    protected $instance;

    public static function __callStatic($method, $args = [])
    {
        if (!$this->instance instanceof static) {
            $this->instance = new static();
        }

        call_user_func_array([$this->instance, $method], $args);
    }
}
