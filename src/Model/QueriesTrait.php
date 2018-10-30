<?php

namespace Amber\ActiveRecord\Model;

use Amber\ActiveRecord\Database\Database;

trait QueriesTrait
{
    private function insert()
    {
        $this->setTimestamps();

        $columns = implode(', ', $this->getAttributesColumns());
        $values = implode(', ', $this->getAttributesValues());

        $this->updateAttributes();

        return "INSERT INTO {$this->name} ({$columns}) VALUES ({$values});";
    }

    private function update()
    {
        $this->setEditedAt();

        $stmt = "UPDATE {$this->name} SET {$this->updates()} WHERE {$this->primary_key} = {$this->primary()};";

        $this->updateAttributes();

        return $stmt;
    }

    private function updateAttributes()
    {
        $this->original = array_replace(
            $this->original,
            $this->diff()
        );
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
}
