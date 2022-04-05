<?php

class ContactEntity extends \DataEntity implements \IBrowsable
{

    public function __construct()
    {
        parent::__construct('contact');
    }
    
    public function getDescriptor()
    {
        return APPLICATION_ROOT . 'config/browser/contact.json';
    }
    
    public function getDataInfo()
    {
        return APPLICATION_ROOT . 'config/form/contact.json';
    }
}

