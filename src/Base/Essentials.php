<?php

namespace Amber\Model\Base;

use Amber\Model\Config\ConfigAwareInterface;
use Amber\Config\ConfigAwareTrait;
use Amber\Cache\CacheAware\CacheAwareInterface;
use Amber\Cache\CacheAware\CacheAwareTrait;
use Amber\Collection\CollectionAware\CollectionAwareInterface;
use Amber\Collection\CollectionAware\CollectionAwareTrait;

abstract class Essentials implements ConfigAwareInterface, CacheAwareInterface, CollectionAwareInterface
{
    use ConfigAwareTrait, CacheAwareTrait, CollectionAwareTrait;
}
