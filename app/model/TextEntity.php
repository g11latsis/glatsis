<?php
namespace app\model;

class TextEntity extends \DataEntity
{

    public function __construct()
    {
        parent::__construct('texts');
    }
    
    /**
     * 
     * @return \TextMapper
     */
    public function loadMappings()
    {
        $result = $this->get();
        
        $tm = new \TextMapper();
        foreach ($result as $o)
        {
            $tm->add($o->varname, $o->varvalue);
        }
        
        return $tm;
    }
}

