<?php

class DataFilterSpec
{
    public function __construct($type)
    {
        $this->type = $type;
    }
    
    public function isText()
    {
        return ($this->type == "text");
    }
    
    public function isLookup()
    {
        return ($this->type == "lookup" || $this->type == "parameter");
    }
    
    public function loadValues()
    {
        if ($this->type == "lookup")
        {
            return $this->loadLookupValues();
        }
        else if ($this->type == "parameter")
        {
            return $this->loadParameterValues();
        }
    }
    
    private function loadLookupValues()
    {
        $definitions = load_lookups();
        if (!empty($definitions->{$this->definition}))
        {
            return db_select($definitions->{$this->definition}->query);
        }
        else 
        {
            return [];
        }
    }
    
    private function loadParameterValues()
    {
        $sql = "select id, name as value from parameter where type = :type order by name";
        return db_select($sql, [':type' => $this->definition]);
    }
    
    private $type;
    public $definition;
    public $name;
    public $fullname;
    public $caption;
}

class DataField
{
    public $fieldName;
    public $fullname;
    public $caption;
    public $width;
    public $alignment;
    public $visible;
    public $sortable;
    public $datatype;
    public $format;
    
    /**
     * @var DataFilterSpec
     */
    public $filter;
    
    public function isDateTime()
    {
        return ($this->datatype == "datetime");
    }
    
    public function isBoolean()
    {
        return ($this->datatype == "boolean");
    }
}

class DataGrid
{
    const DefaultPageSize = 10;
    
    private $title;
    private $newTitle;
    private $query;
    private $countQuery;
    private $pageSize;
    
    /**
     * @var DataField[]
     */
    private $fields;

    public function __construct()
    {
        $this->title = '';
        $this->fields = [];
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getNewTitle()
    {
        return $this->newTitle;
    }
    
    public function addField(DataField $field)
    {
        $this->fields[] = $field;
    }
    
    public function getFields()
    {
        return $this->fields;
    }
    
    public function getPageSize()
    {
        return $this->pageSize;
    }
    
    public function load($jsonfile)
    {
        $o = \json_decode(file_get_contents($jsonfile));
        
        $this->title = $o->title;
        $this->newTitle = _t($o->newTitle);
        $this->query = $o->query;
        $this->countQuery = $o->count;
        $this->pageSize = isset($o->pageSize) 
                        ? intval($o->pageSize) 
                        : self::DefaultPageSize;
        
        foreach ($o->fields as $f)
        {
            $fld = new DataField();
            $fld->fieldName = $f->name;
            $fld->fullname = $f->fullname;
            $fld->caption = safestr($f->caption);
            $fld->width = intval($f->width);
            $fld->alignment = !empty($f->align) ? $f->align : "left";
            $fld->visible = isset($f->visible) ? boolval($f->visible) : true;
            $fld->sortable = !empty($f->sortable) ? boolval($f->sortable) : false;
            $fld->datatype = !empty($f->datatype) ? $f->datatype : "string";
            $fld->format = isset($f->format) ? $f->format : null;
            
            $fld->filter = null;
            if (isset($f->filter))
            {
                $fld->filter = new DataFilterSpec($f->filter->type);
                $fld->filter->caption = $fld->caption;
                $fld->filter->name = $fld->fieldName;
                $fld->filter->fullname = $fld->fullname;
                if (isset($f->filter->definition))
                {
                    $fld->filter->definition = $f->filter->definition;
                }
            }
            
            $this->addField($fld);
        }
        
    }
    
    public function getData(DataEntity $entity, QueryOptions $options = null)
    {
        $data = new stdClass();
        $data->total = intval(db_select_scalar($this->countQuery));
        $data->rows = [];
        //debug($options->adaptQuery($this->query));debug($options->getFilterBindings());exit;
        $rawRows = db_select($this->query, $options->getFilterBindings(), '', $options);
        foreach ($rawRows as $row)
        {
            foreach ($this->fields as $f)
            {
                if ($f->isDateTime() && !empty($f->format))
                {
                    $row->{$f->fieldName} = format_db_date($row->{$f->fieldName}, $f->format);
                }
                else if ($f->isBoolean())
                {
                    $row->{$f->fieldName} = (intval($row->{$f->fieldName}) > 0) ? _t('label.yes') : _t('label.no');
                }
            }
            
            $data->rows[] = $row;
        }
        
        
        return $data;
    }
    
    /**
     * @return DataFilterSpec[]
     */
    public function getFilters()
    {
        $filters = [];
        foreach ($this->fields as $f)
        {
            if (!empty($f->filter))
            {
                $filters[] = $f->filter;
            }
        }
        
        return $filters;
    }
    
    
}

