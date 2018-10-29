<?php

namespace Amber\ActiveRecord\Config;

use Amber\Config\ConfigAwareInterface as BaseConfig;

interface ConfigAwareInterface extends BaseConfig
{
    const PACKAGE_NAME = 'active_record';

    const DEFAULT_DRIVER = 'mysql';

    const DRIVERS = [
        //'array' => \Amber\Model\Drivers\ArrayDriver::class,
        //'json' => \Amber\Model\Drivers\JsonDriver::class,
    ];
}
