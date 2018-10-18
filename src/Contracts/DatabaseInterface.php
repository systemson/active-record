<?php

namespace Amber\Model\Contracts;

interface DatabaseInterface extends EssentialsInterface
{
	public function hasConnection(): bool;

	public function setConnection(iterable $options): void;

	public function connect(): DatabaseInterface;

	public function create(string $name): bool;

	public function exists(string $name): bool;

	public function drop(string $name): bool;
}