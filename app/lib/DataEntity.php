<?php

interface IBrowsable
{
    public function getDescriptor();
    public function getDataInfo();
}

class DataEntity
{
    private const IdFieldName = 'id';
    
    private $baseTable;
    
    public function __construct($tableName)
    {
        $this->baseTable = $tableName;
    }
    
    public function get(array $filters = [])
    {
        $sql = "select * from {$this->baseTable}";
        $bindings = [];
        
        if (!empty($filters))
        {
            $where = [];
            foreach ($filters as $fieldName => $fieldValue)
            {
                $where[] = "$fieldName = :$fieldName";
                $bindings[":$fieldName"] = $fieldValue;
            }
            
            $sql .= " where " . implode(' and ', $where);
        }
        
        return db_select($sql, $bindings);
    }
    
    public function getSingle(array $filters = [])
    {
        $sql = "select * from {$this->baseTable}";
        $bindings = [];
        
        if (!empty($filters))
        {
            $where = [];
            foreach ($filters as $fieldName => $fieldValue)
            {
                $where[] = "$fieldName = :$fieldName";
                $bindings[":$fieldName"] = $fieldValue;
            }
            
            $sql .= " where " . implode(' and ', $where);
        }
        
        return db_select_single($sql, $bindings);
    }
    
    public function getById($id)
    {
        return $this->getSingle([self::IdFieldName => $id]);
    }
        
    public function save(array $record)
    {
        if (empty($record[self::IdFieldName]))
        {
            // Insert record
            $sql = "insert into {$this->baseTable} ({0}) values ({1})";
            
            $fieldNames = "";
            $fieldValues = "";
            $bindings = [];
            foreach ($record as $key => $value)
            {
                $fieldNames .= $key . ',';
                $fieldValues .= ':' . $key . ',';
                $bindings[':' . $key] = $value;
            }
            
            $sql = str_replace("{0}", rtrim($fieldNames, ','), $sql);
            $sql = str_replace("{1}", rtrim($fieldValues, ','), $sql);
            
            return db_insert($sql, $bindings);
        }
        else
        {
            // Update record
            $sql = "update {$this->baseTable} set {0} where id = :id";
            
            $updateTerms = "";
            $bindings = [];
            foreach ($record as $key => $value)
            {
                if ($key != self::IdFieldName)
                {
                    $updateTerms .= "$key = :$key,";
                    $bindings[":$key"] = $value;
                }
            }
            
            $bindings[':id'] = $record[self::IdFieldName];
            $sql = str_replace("{0}", rtrim($updateTerms, ','), $sql);
            
            return db_update($sql, $bindings);
        }
    }
    
    public function delete(array $filters = [])
    {
        $sql = "delete from {$this->baseTable}";
        
        $bindings = [];
        if (!empty($filters))
        {
            $where = [];
            foreach ($filters as $key => $value)
            {
                $where[] = "$key = :$key";
                $bindings[":$key"] = $value;
            }
            
            $sql .= ' where ' . implode(' and ', $where);
        }
        
        return db_delete($sql, $bindings);
    }
    
    public function deleteById($id)
    {
        return $this->delete([self::IdFieldName => $id]);
    }
    
    public function isUnique($id, array $filters)
    {
        $sql = "select id from {$this->baseTable}";
        
        $where = [];
        $bindings = [];
        foreach ($filters as $fieldName => $fieldValue)
        {
            $where[] = "$fieldName = :$fieldName";
            $bindings[":$fieldName"] = $fieldValue;
        }
        
        $sql .= " where " . implode(' and ', $where);
        if (!empty($id))
        {
            $sql .= " and id <> :id";
            $bindings[':id'] = $id;
        }
        
        return empty(db_select_scalar($sql, $bindings));
    }
}


$eUspr = new DataEntity('user_profile');