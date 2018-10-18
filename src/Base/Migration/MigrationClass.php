<?php

namespace Amber\Model\Base\Migration;

use Amber\Model\Base\Essentials;

abstract class MigrationClass extends Essentials implements MigrationInterface
{
    use MigrationTrait;
}
