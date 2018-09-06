<?php

namespace Amber\Model\Base\Driver;

use Ds\Collection;

interface DriverInterface
{
    public static function find(integer $id): Collection;

    public static function select(...$columns): DriverInterface;

    public static function from(string $table): DriverInterface;

    public static function where(string $column, string $operator, $value): DriverInterface;

    public static function orderBy(string $column): DriverInterface;

    public static function limit(integer $n): DriverInterface;

    public static function get(): Collection;

    public static function first(): Collection;

    public static function last(): Collection;

    public static function save(): bool;

    public static function toSql(): string;

    public static function update(): bool;

    public static function createTable(): string;

    public static function toJson(): string;

    public function store(): bool;
}
