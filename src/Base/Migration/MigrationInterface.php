<?php

namespace Amber\Model\Base\Migration;

interface MigrationInterface
{
    public function createTable($model): bool;

    public function hasTable($model): bool;

    public function updateTable($model): bool;

    public function dropTable($model): bool;
}
