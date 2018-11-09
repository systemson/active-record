<?php

namespace Amber\Gemstone\Config;

use Amber\Config\ConfigAwareInterface as BaseConfig;

interface ConfigAwareInterface extends BaseConfig
{
    const PACKAGE_NAME = 'gemstone';

    const DRIVERS = [
        //'array' => \Amber\Model\Drivers\ArrayDriver::class,
        //'json' => \Amber\Model\Drivers\JsonDriver::class,
    ];
}
