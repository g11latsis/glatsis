<?php

class UserProfileDataObject extends DataObject
{
    public $name;
    public $isSuper;
    public $level;
    public $active = 1;
    
    public function __construct()
    {
        $this->fields = [
            'name' => [
                'mandatory' => true
            ],
            'isSuper' => [
                'mandatory' => true
            ],
            'level' => [
                'mandatory' => true
            ],
            'active' => [
                'mandatory' => true
            ],
        ];
    }
    
    
}
