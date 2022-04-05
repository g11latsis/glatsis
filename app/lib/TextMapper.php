<?php


class TextMapper
{
    private $vars;
    
    public function __construct()
    {
        $this->vars = [];
    }
    
    public function add($varname, $varvalue)
    {
        $this->vars[$varname] = $varvalue;
    }
    
    public function get($varname)
    {
        return (isset($this->vars[$varname]) ? $this->vars[$varname] : $varname);
    }
    
    public function safe($varname)
    {
        return (isset($this->vars[$varname]) ? safestr($this->vars[$varname]) : $varname);
    }
}

