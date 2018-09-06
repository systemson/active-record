<?php

namespace Amber\Model\Base\Migration;

interface MigrationInterface
{
    public function create(string $table = null): bool;

    public function update(string $table = null): bool;

    public function drop(string $table = null): bool;
}
