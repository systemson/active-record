<?php

namespace Amber\Gemstone\Common;

use Ds\Collection;

/**
 *
 */
interface AttributeCollectionInterface extends Collection
{
    public function setValue($key, $value);

    public function getValue($key);

    public function getRules($key): iterable;

    public function lock(): AttributeCollectionInterface;
}
