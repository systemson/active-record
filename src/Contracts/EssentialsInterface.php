<?php

namespace Amber\Model\Contracts;

interface EssentialsInterface
{
	public function toSql(): string;

	public function toString(): string;

	public function save(): string;
}