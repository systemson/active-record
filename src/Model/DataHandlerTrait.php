<?php

namespace Amber\ActiveRecord\Model;

use Amber\ActiveRecord\Database\Database;
use Carbon\Carbon;
use PDO;
use PDOStatement;

/** 
 * @todo SHOULD store it's own PDO instance.
 * @todo NEEDS refactoring.
 */
trait DataHandlerTrait
{
    private $pdo;

    private function pdo(): PDO
    {
    }

    private function insert()
    {
        $success = Database::run($this->getInsertStmt());
        
        if ($success) {
            $id = Database::pdo()->lastInsertId();
            $array = Database::getArray("SELECT * FROM {$this->name} WHERE {$this->primary_key} = $id;");

            $this->updateAttributes($array, $id);
        }

        return $success;
    }

    private function getInsertStmt(): string
    {
        $this->setTimestamps();

        $columns = implode(', ', $this->getAttributesColumns());
        $values = implode(', ', $this->getAttributesValues());


        return "INSERT INTO {$this->name} ({$columns}) VALUES ({$values});";
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

    private function update()
    {
        return Database::run($this->getUpdateStmt());
    }

    private function getUpdateStmt()
    {
        $this->setEditedAt();

        $stmt = "UPDATE {$this->name} SET {$this->updatable()} WHERE {$this->primary_key} = {$this->primary()};";

        return $stmt;
    }

    private function updatable()
    {
        $ret = [];

        foreach ($this->diff() as $key => $value) {
            $ret[] = "{$key} = {$this->value($value)}";
        }

        return implode(', ', $ret);
    }

    private function diff()
    {
        return array_diff_assoc($this->attributes, $this->original);
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

    public function save()
    {
        if (empty($this->original)) {
            return $this->insert();
        } elseif (!empty($this->diff())) {
            return $this->update();
        }

        return false;
    }

    public function delete()
    {
        return Database::run("DELETE FROM {$this->name} WHERE {$this->primary_key} = {$this->primary()};");
    }

    private function getDeleteStmt()
    {
        $this->setEditedAt();

        $stmt = "UPDATE {$this->name} SET {$this->updatable()} WHERE {$this->primary_key} = {$this->primary()};";

        return $stmt;
    }

    private function value($value)
    {
        return var_export($value, true);
    }

    private function find($id)
    {
        $id = [':pk_id' => $this->value($id)];

        return Database::get(
            "SELECT * FROM {$this->name} WHERE {$this->primary_key} = :pk_id;",
            $id,
            static::class
        );
    }
}
