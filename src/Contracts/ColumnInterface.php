<?php

namespace Amber\Model\Contracts;

interface ColumnInterface extends EssentialsInterface
{
    public function name(string $name): ColumnInterface;

    public function type(string $type): ColumnInterface;

    public function size(int $size): ColumnInterface;

    public function nullable(bool $nullable): ColumnInterface;

    public function autoincrement(bool $incrementable): ColumnInterface;

    public function primary(string $name): ColumnInterface;
}
