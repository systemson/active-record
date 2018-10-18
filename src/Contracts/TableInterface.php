<?php

namespace Amber\Model\Contracts;

interface TableInterface extends EssentialsInterface
{
	public function name(string $name);

	public function exists(): bool;

	public function create(iterable $columns = [], iterable $options = []): TableInterface;

	public function rename(string $new_name): bool;

	public function update(iterable $options = []): bool;

	public function drop(string $name): bool;

	public function foreign(string $name): TableInterface;

	public function primary(string $name): TableInterface;
}