<?php

abstract class DataObject
{
    public $id = null;
    
    protected $fields;
    
    public function checkMandatoryFields()
    {
        $emptyFields = [];
        
        $objProps = get_object_vars($this);
        foreach ($this->fields as $property => $attrs)
        {
            if ($attrs['mandatory'] && empty($objProps[$property]))
            {
                $emptyFields[] = $property;
            }
        }
        
        return $emptyFields;
    }
    
}

