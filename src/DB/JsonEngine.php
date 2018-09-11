<?php

namespace Amber\Model\DB;

use Amber\Model\Base\Essentials;
use Amber\Cache\Driver\JsonCache;

class JsonEngine extends Essentials
{

    protected $tables = [];

    protected $name = 'json_db';

    public function __construct($config = [])
    {
        $this->setConfig(['cache' => ['file_cache_path' => 'tmp/database/']]);
        $this->getCache('json');
    }

    public function pick()
    {
        if (!$this->hasDB($this->name)) {
            $this->tables = $this->getCache()->get($this->name);
        }
    }

    public function drop()
    {
    }

    public function hasDB()
    {
        return $this->getCache()->has($this->name);
    }

    public function createDB()
    {
        if (!$this->hasDB($this->name)) {
            return $this->getCache()->set($this->name, $this->tables);
        }

        return false;
    }

    public function hasTable($table, $columns = [])
    {
        if (!$this->hasDB($this->name)) {
            $this->tables = $this->getCache()->set($this->name, $this->tables);
        }

        return false;
    }

    public function createTable($table, $columns = [])
    {
        if ($this->hasDB($this->name)) {
            $this->pick();

            $this->tables[$table] = $columns;

            $this->getCache()->set($table, []);
            return $this->getCache()->set($this->name, $this->tables);
        }

        return false;
    }

    public function insert($table, $records)
    {
        return false;
    }

    public function dropDB()
    {
        return $this->getCache()->clear();
    }
}
