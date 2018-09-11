<?php

namespace Amber\Model;

use Amber\Model\Base\Migration\MigrationClass;

class MigrationHandler extends MigrationClass
{
    public function __construct($config = [])
    {
        $this->setConfig($config);
    }

    public function __call($method, $args)
    {
    	call_user_func_array([$this->getDriver(), $method], $args);
    }
}
