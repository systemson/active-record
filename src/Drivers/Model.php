<?php

namespace Amber\Model\Drivers;

use PDO;
use Amber\Config\Config;
use Amber\Model\Config\ConfigAwareInterface;
use Amber\Model\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;

class Model //implements ConfigAwareInterface
{
    //use ConfigAwareTrait, SingletonTrait;

    protected $name;
    protected $primary_key = 'id';

    private $attributes = [];
    private $original = [];
}
