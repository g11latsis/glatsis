<?php

class CompanyDataObject extends DataObject
{
    public $code;
    public $title;
    public $active=1;
    
    public function __construct()
    {
        $this->fields = [
            'code' => [
                'mandatory' => true
            ],
            'title' => [
                'mandatory' => true
            ],
            'active' => [
                'mandatory' => true
            ],
        ];
    }

}