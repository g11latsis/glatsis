<?php

class ContactDataObject extends DataObject
{
    public $code;
    public $firstname;
    public $lastname;
    public $fathername;
    public $mothername;
    public $degreeID;

    
    public function __construct()
    {
        $this->fields = [
            'code' => [
                'mandatory' => true
            ],
            'firstname' => [
                'mandatory' => true
            ],
            'lastname' => [
                'mandatory' => true
            ],
            'fathername' => [
                'mandatory' => true
            ],
            'mothername' => [
                'mandatory' => true
            ],
            'degreeID' => [
                'mandatory' => true
            ],
        ];
    }

}