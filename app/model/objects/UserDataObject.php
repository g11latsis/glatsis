<?php

class UserDataObject extends DataObject
{
    public $profileId;
    public $contactId;
    public $username;
    public $password;
    public $active = 1;
    
    public function __construct()
    {
        $this->fields = [
            'profileId' => [
                'mandatory' => true
            ],
            'contactId' => [
                'mandatory' => true
            ],
            'username' => [
                'mandatory' => true
            ],
            'password' => [
                'mandatory' => true
            ],
            'active' => [
                'mandatory' => true
            ],
        ];
    }
    
    
}

class UserViewObject
{
    public $id;
    public $profileId;
    public $contactId;
    public $username;
    
    public $code;
    public $firstname;
    public $lastname;
    
    public $superuser;
    public $level;
    
    public function fullname()
    {
        return "{$this->firstname} {$this->lastname}";
    }
}

