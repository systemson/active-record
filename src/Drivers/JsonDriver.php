<?php

namespace Amber\Model\Drivers;

use Amber\Model\Base\Essentials;
use Amber\Model\Base\Driver\DriverInterface;

class JsonDriver extends Essentials implements DriverInterface
{
    protected function pick()
    {
        $cacheKey = $this->getConfig('model_cache_folder') . $this->getConfig('model_cache_name');
        $cache = $this->getCache()->get($cacheKey);

        $this->setCollection(new Collection($cache));
    }

    public function createTable($model): bool
    {
        $key = $model->getTable();
        $columns = [];

        $this->getCollection()->put($key, $columns);

        return true;
    }

    public function updateTable($model): bool
    {
        $key = $model->getTable();
        $columns = array_keys($model->columns);

        $table = $this->getCollection()->find($key);

        $table->setColumns($columns);

        $this->getCollection()->put($key, $table->toArray());

        return true;
    }

    public function hasTable($model): bool
    {
        return $this->getCollection()->has($model->getTable());
    }

    public function dropTable($model): bool
    {
        return $this->getCollection()->delete($model->getTable());
    }
}
