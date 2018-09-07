<?php

namespace Amber\Model\Migration;

use Amber\Model\Base\Essentials;

class JsonMigration extends Essentials
{
    protected $data;

    public function createDB(string $name = null): bool
    {
        if (isset($name)) {
            $this->database = $name;
        }

        return $this->getCache()->set($this->database, []);
    }

    public function dropDB(string $name = null): bool
    {
        if (isset($name)) {
            $this->database = $name;
        }

        return $this->getCache()->delete($this->database);
    }

    public function createTable(string $name, iterable $columns): bool
    {
        if ($this->dbExists()) {
            $this->data = $this->getCache()->get($this->database);

            $this->data[$name] = $columns;

            return $this->getCache()->set($this->database, $this->data);
        }

        return false;
    }

    public function dropTable(string $name, iterable $entities): bool
    {
        if ($this->dbExists()) {
            $this->data = $this->getCache()->get($this->database);

            unset($this->data[$name]);

            return $this->getCache()->set($this->database, $this->data);
        }

        return false;
    }

    protected function dbExists(string $name = null)
    {
        if (isset($name)) {
            $this->database = $name;
        }

        return $this->getCache()->has($this->database);
    }
}
