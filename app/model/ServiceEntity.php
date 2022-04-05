<?php


class ServiceEntity extends DataEntity implements IBrowsable
{

    public function __construct()
    {
        parent::__construct('service');
    }
    
    public function getDescriptor()
    {
        return APPLICATION_ROOT . 'config/browser/service.json';
    }
    
    public function getDataInfo()
    {
        return APPLICATION_ROOT . 'config/form/service.json';
    }
}

