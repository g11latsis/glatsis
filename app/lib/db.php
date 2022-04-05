<?php

class QueryFilter
{
    public $name;
    public $value;
    public $operator;
}

class QueryOptions
{
    private $offset = 0;
    private $limit = 0;
    private $sortings = [];
    
    /**
     * @var QueryFilter[]
     */
    private $filters = [];
    
    public function setLimits($offset, $limit)
    {
        $this->offset = intval($offset);
        $this->limit = intval($limit);
        
        if ($this->offset < 0)
        {
            $this->offset = 0;
        }
        
        if ($this->limit < 0)
        {
            $this->limit = 0;
        }
    }
    
    public function addSorting($fieldName, $ascending = true)
    {
        $this->sortings[$fieldName] = boolval($ascending);
    }
    
    public function addFilter($name, $value, $operator)
    {
        $filter = new QueryFilter();
        $filter->name = $name;
        $filter->value = $value;
        $filter->operator = $operator;
        
        $this->filters[] = $filter;
    }
    
    public function adaptQuery($sql)
    {
        return $this->addLimits(
                    $this->addSortings(
                        $this->addFilters($sql)));
    }
    
    public function getFilterBindings()
    {
        $bindings = [];
        foreach ($this->filters as $f)
        {
            if (!empty($f->value))
            {
                $value = ($f->operator === 'like')
                       ? "%{$f->value}%"
                       : $f->value;
                $bindings[':' . str_replace('.', '', $f->name)] = $value;
            }
        }
        
        return $bindings;
    }
    
    private function addFilters($sql)
    {
        if (!empty($this->filters))
        {
            $where = [];
            foreach ($this->filters as $f)
            {
                if (!empty($f->value))
                {
                    $operator = ($f->operator === 'like') ? ' like ' : ' = ';
                    $where[] = $f->name . $operator . ':' . str_replace('.', '', $f->name);
                }
            }
            
            if (!empty($where))
            {
                $pos = stripos($sql, 'where');
                if ($pos === false)
                {
                    $sql .= ' where ';
                }
                else
                {
                    $sql .= ' and ';
                }
                
                $sql .= implode(" and ", $where);
            }
        }   
        
        return $sql;
    }
    
    private function addSortings($sql)
    {
        if (!empty($this->sortings))
        {
            $sql .= " order by ";
            foreach ($this->sortings as $field => $ascending)
            {
                $sql .= "$field " . ($ascending ? 'asc' : 'desc') . ',';
            }
            $sql = rtrim($sql, ',');
        }
        
        return $sql;
    }
    
    private function addLimits($sql)
    {
        if ($this->limit > 0)
        {
            if ($this->offset > 0)
            {
                $sql .= " LIMIT {$this->offset}, {$this->limit}";
            }
            else
            {
                $sql .= " LIMIT {$this->limit}";
            }
        }
        
        return $sql;
    }
    
    
}

function db_connection()
{
    static $pdo;
    if (empty($pdo))
    {
        $connection_data = load_settings('connections/system');
        $pdo = new \PDO(
            $connection_data['dsn'], 
            $connection_data['username'], 
            $connection_data['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
    return $pdo;
}


function db_prepare($sql, array $bindings = [])
{
    $pdo = db_connection();
    
    $stmt = $pdo->prepare($sql);
    foreach ($bindings as $key => $value)
    {
        $stmt->bindValue($key, $value);
    }
    
    return $stmt;
}

function db_select($sql, array $bindings = [], $assocKey = '', QueryOptions $options = null)
{
    if ($options != null)
    {
        $sql = $options->adaptQuery($sql);
    }
    
    $stmt = db_prepare($sql, $bindings);
    $stmt->execute();
    
    $result = [];
    if (!empty($assocKey))
    {
        while ($record = $stmt->fetch(PDO::FETCH_OBJ))
        {
            $result[$record->$assocKey] = $record;
        }
    }
    else 
    {
        while ($record = $stmt->fetch(PDO::FETCH_OBJ))
        {
            $result[] = $record;
        }
    }
    
    return $result;
}

function db_select_single($sql, array $bindings = [])
{
    $stmt = db_prepare($sql, $bindings);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_OBJ);
}

function db_select_scalar($sql, array $bindings = [])
{
    $stmt = db_prepare($sql, $bindings);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return !empty($record) ? reset($record) : false;
}

function db_insert($sql, array $bindings = [])
{
    $stmt = db_prepare($sql, $bindings);
    $stmt->execute();
    
    return db_connection()->lastInsertId();
}

function db_update($sql, array $bindings = [])
{
    $stmt = db_prepare($sql, $bindings);
    return $stmt->execute();
}

function db_delete($sql, array $bindings = [])
{
    $stmt = db_prepare($sql, $bindings);
    return $stmt->execute();
}

