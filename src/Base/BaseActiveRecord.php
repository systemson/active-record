<?php

namespace Amber\Model\Base;

use Amber\Config\ConfigAwareInterface;
use Amber\Config\ConfigAwareTrait;
use Amber\Collection\CollectionAware\CollectionAwareInterface;
use Amber\Collection\CollectionAware\CollectionAwareTrait;

abstract class BaseActiveRecord implements ConfigAwareInterface, CollectionAwareInterface
{
    use ConfigAwareTrait, CollectionAwareTrait;
}
