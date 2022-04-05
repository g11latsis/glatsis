<?php

class DataFormField
{
    public $name;
    public $caption;
    public $colindex = 1;
    public $type;
    public $definition;
    public $visible = true;
    public $control;
    public $mandatory = false;
    public $unique = false;
    public $minlen = 0;
    public $maxlen = 0;
    public $minval;
    public $maxval;
    public $isViewField;
    public $value;
    
    public function isHidden()
    {
        return ($this->control === 'hidden');
    }
    
    public function isTextbox()
    {
        return ($this->control === 'textbox');
    }
    
    public function isCombobox()
    {
        return ($this->control === 'parameter'
            ||  $this->control === 'combobox');
    }
    
    public function isCheckbox()
    {
        return ($this->control === "checkbox");
    }
    
    public function isInt()
    {
        return ($this->type === 'int');
    }
    
    public function isString()
    {
        return ($this->type === 'string');
    }
    
    public function getDefaultValue()
    {
        if ($this->isInt())
        {
            return 0;
        }
        else if ($this->isString())
        {
            return '';
        }
        
        return null;
    }
    
    public function loadValues()
    {
        if ($this->control == "lookup")
        {
            return $this->loadLookupValues();
        }
        else if ($this->control == "parameter")
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
}

class DataFormTab
{
    public $caption;
    
    /**
     * 
     * @var DataFormField[]
     */
    private $fields = [];
    
    public function addField(DataFormField $f)
    {
        $this->fields[] = $f;
    }
    
    /**
     * 
     * @return DataFormField[]
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    public function getColumnCount()
    {
        $colindexes = [];
        foreach ($this->fields as $f)
        {
            $colindexes[$f->colindex] = $f->colindex;
        }
        
        return count($colindexes);
    }
}

class DataForm
{
    /**
     * @var DataEntity
     */
    private $entity;
    
    private $recordId;
    
    /**
     * @var DataFormTab[]
     */
    private $tabs;
    
    /**
     *
     * @var DataFormField[]
     */
    private $posted;
    
    public function __construct(DataEntity $entity, $recordId, $jsonfile = null)
    {
        $this->entity = $entity;
        $this->recordId = $recordId;
        if (!empty($jsonfile))
        {
            $this->load($jsonfile);
        }
    }
    
    /**
     * 
     * @return DataFormTab[]
     */
    public function getTabs()
    {
        return $this->tabs;
    }
    
    public function load($jsonfile)
    {
        $o = \json_decode(file_get_contents($jsonfile));
        $dataObject = $this->entity->getById($this->recordId);
        
        foreach ($o->tabs as $tabinfo)
        {
            $tab = new DataFormTab();
            $tab->caption = _t($tabinfo->caption);
            
            if (!empty($tabinfo->fields))
            {
                foreach ($tabinfo->fields as $fieldinfo)
                {
                    $field = new DataFormField();
                    $field->name = $fieldinfo->name;
                    $field->caption = isset($fieldinfo->caption) ? _t($fieldinfo->caption) : '';
                    $field->colindex = isset($fieldinfo->colindex) ? intval($fieldinfo->colindex) : 1;
                    $field->type = strtolower($fieldinfo->type);
                    $field->control = strtolower($fieldinfo->control);
                    $field->visible = boolval($fieldinfo->visible);
                    $field->mandatory = boolval($fieldinfo->mandatory);
                    $field->unique = boolval($fieldinfo->unique);
                    $field->definition = isset($fieldinfo->definition) ? $fieldinfo->definition : null;
                    
                    if (isset($fieldinfo->minlen))
                    {
                        $field->minlen = intval($fieldinfo->minlen);
                    }
                    
                    if (isset($fieldinfo->maxlen))
                    {
                        $field->maxlen = intval($fieldinfo->maxlen);
                    }
                    
                    if (isset($fieldinfo->minval))
                    {
                        $field->minval = intval($fieldinfo->minval);
                    }
                    
                    if (isset($fieldinfo->maxval))
                    {
                        $field->maxval = intval($fieldinfo->maxval);
                    }
                    
                    $field->isViewField = !empty($fieldinfo->isViewField);
                    $field->value = !empty($dataObject) 
                                  ? $dataObject->{$field->name} 
                                  : $field->getDefaultValue();
                    
                    $tab->addField($field);
                }
            }
            
            $this->tabs[] = $tab;
        }
    }
    
    public function collectPosted()
    {
        $this->posted = [];
        foreach ($this->tabs as $tabinfo)
        {
            foreach ($tabinfo->getFields() as $fieldinfo)
            {
                if ($fieldinfo->isInt())
                {
                    $fieldinfo->value = http_post_int($fieldinfo->name);
                }
                else if ($fieldinfo->isCheckbox())
                {
                    $fieldinfo->value = http_post_bool($fieldinfo->name) ? 1 : 0;
                }
                else
                {
                    $fieldinfo->value = http_post_string($fieldinfo->name);
                }
                
                $this->posted[$fieldinfo->name] = $fieldinfo;
            }
        }
    }
    
    public function isNew()
    {
        return ($this->posted['id']->value <= 0);
    }
    
    public function getViewFieldValue()
    {
        foreach ($this->posted as $fieldinfo)
        {
            if (!empty($fieldinfo->isViewField))
            {
                return $fieldinfo->value;
            }
        }
        
        return '';
    }
    
    public function checkEmptyFields()
    {
        $mandatory = [];
        foreach ($this->posted as $fieldname => $fieldinfo)
        {
            if ($fieldinfo->mandatory && empty($fieldinfo->value))
            {
                $mandatory[$fieldname] = [
                    'message' => '',
                    'caption' => _t($fieldinfo->caption)
                ];
            }
        }
        
        return $mandatory;
    }
    
    public function checkUniqueFields()
    {
        $unique = [];
        foreach ($this->posted as $fieldinfo)
        {
            if ($fieldinfo->unique && $fieldinfo->name !== 'id')
            {
                $isUnique = $this->entity->isUnique(
                    $this->posted['id']->value, 
                    [$fieldinfo->name => $fieldinfo->value]
                );
                
                if (!$isUnique)
                {
                    $unique[$fieldinfo->name] = [
                        'caption' => $fieldinfo->caption,
                        'value' => $fieldinfo->value
                    ];
                }
            }
        }
        
        return $unique;
    }
    
    public function save()
    {
        $record = [];
        foreach ($this->posted as $fieldname => $fieldinfo)
        {
            $record[$fieldname] = $fieldinfo->value;
        }
        
        return $this->entity->save($record);
    }
    
    
}