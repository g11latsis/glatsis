<?php


class CompanyEntity extends \DataEntity implements \IBrowsable
{

    public function __construct()
    {
        parent::__construct('company');
    }
    
    public function getDescriptor()
    {
        return APPLICATION_ROOT . 'config/browser/company.json';
    }
    
    public function getDataInfo()
    {
        return APPLICATION_ROOT . 'config/form/company.json';
    }
}

