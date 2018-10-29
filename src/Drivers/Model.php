<?php

namespace Amber\Model\Drivers;

use PDO;
use PDOStatement;
use Amber\Config\Config;
use Amber\Model\Config\ConfigAwareInterface;
use Amber\Model\Config\ConfigAwareTrait;
use Amber\Utils\Traits\SingletonTrait;
use Carbon\Carbon;

class Model
{
	use SingletonTrait;

    protected $name;
    protected $primary_key = 'id';
    protected $dates = true;

    private $pk;
    private $attributes = [];
    private $original = [];

    public function __construct(PDOStatement $stmt = null)
    {
    	if (!is_null($stmt)) {
    		$array = $stmt->fetch();

    		$this->attributes = $array;
    		$this->original = $array;
            $this->pk = $array[$this->primary_key] ?? null;
    	}
    }

    private function insert()
    {
    	$this->setTimestamps();

    	$columns = implode(', ', $this->getAttributesColumns());
    	$values = implode(', ', $this->getAttributesValues());

    	return "INSERT INTO {$this->name} ({$columns}) VALUES ({$values});";
    }

    private function update()
    {
        $this->setEditedAt();

        $stmt = "UPDATE {$this->name} SET {$this->updates()} WHERE {$this->primary_key} = {$this->primary()};";

        $this->original = array_replace(
            $this->original,
            $this->diff()
        );

        return $stmt;
    }

    public function diff()
    {
    	return array_diff_assoc($this->attributes, $this->original);
    }

    private function updates()
    {
        foreach ($this->diff() as $key => $value) {
            $ret[] = "{$key} = {$this->value($value)}";
        }

        return implode(', ', $ret);
    }

    private function setTimestamps()
    {
    	if ($this->dates) {
    		$now = (string) Carbon::now();

    		$this->attributes['created_at'] = $now;
    		$this->attributes['edited_at'] = $now;
    	}
    }

    private function setEditedAt()
    {
        if ($this->dates) {
            $this->attributes['edited_at'] = (string) Carbon::now();
        }
    }

    private function setDeletedAt()
    {
        if ($this->softdelete) {
            $this->attributes['deleted_at'] = (string) Carbon::now();
        }
    }

    private function getAttributesColumns()
    {
        return array_keys($this->attributes);
    }

    private function getAttributesValues()
    {
    	return array_map(
    		function ($value) {
				return $this->value($value, true);
			},
			$this->attributes
		);
    }

    private function value($value)
    {
    	return var_export($value, true);
    }

    public function save()
    {
        if (empty($this->original)) {
            return (Database::pdo()->prepare($this->insert()))->execute();
        } elseif (!empty($this->diff())) {
            return (Database::pdo()->prepare($this->update()))->execute();
        }

    	return false;
    }

    public function delete()
    {
        return Database::pdo()->exec("DELETE FROM {$this->name} WHERE {$this->primary_key} = {$this->primary()};") > 0;
    }

    private function find($id)
    {
    	$id = [':pk_id' => $this->value($id, true)];

    	return Database::get(
    		"SELECT * FROM {$this->name} WHERE {$this->primary_key} = :pk_id;",
    		$id,
    		static::class
    	);
    }

    private function primary()
    {
        return $this->pk ?? $this->{$this->primary_key};
    }

    public function __set($key, $value)
    {
    	$this->attributes[$key] = $value;
    }

    public function __get($key)
    {
    	return $this->attributes[$key] ?? null;
    }
}
