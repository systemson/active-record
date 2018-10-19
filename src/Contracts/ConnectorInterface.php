<?php

namespace Amber\Model\Contracts;

interface ConnectorInterface
{
    public function hasConnection(): bool;

    public function setConnection(iterable $options): void;

    public function connect(): self;
}
