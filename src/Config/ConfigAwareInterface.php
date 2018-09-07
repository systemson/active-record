<?php

namespace Amber\Model\Config;

use Amber\Config\ConfigAwareInterface as BaseConfig;

interface ConfigAwareInterface extends BaseConfig
{
    const DEFAULT_DRIVER = 'json';

    const DRIVERS = [
        'json' => \Amber\Model\Drivers\JsonDriver::class,
    ];
}
