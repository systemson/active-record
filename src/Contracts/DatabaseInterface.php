<?php

namespace Amber\Model\Contracts;

interface DatabaseInterface extends EssentialsInterface
{
    public function create(string $name): bool;

    public function exists(string $name): bool;

    public function drop(string $name): bool;
}
