<?php

namespace Amber\Model\Config;

use Amber\Config\ConfigAwareInterface as BaseConfig;

interface ConfigAwareInterface extends BaseConfig
{
    const DEFAULT_DRIVER = 'json';

    const DRIVERS = [
        'array' => \Amber\Model\Drivers\ArrayDriver::class,
        'json' => \Amber\Model\Drivers\JsonDriver::class,
    ];
}
