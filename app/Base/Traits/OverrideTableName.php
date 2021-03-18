<?php

namespace App\Base\Traits;

trait OverrideTableName
{

    public function getTable()
    {
        if (str_contains($this->table, '.')) return $this->table;

        $databaseName = config('database.connections.' . $this->getConnectionName() . '.database');
        return implode('.', array($databaseName, $this->table));
    }

}
