<?php

class MenuItem
{
    public $title;
    public $entityId;
    public $baseTable;
    public $url;
    public $isBrowser;
    
    public function getUrl()
    {
        if ($this->isBrowser)
        {
            return url('browser', 'browser_list', ['eid' => $this->entityId]);
        }
        
        return $this->url;
    }
}

class MenuEntity extends DataEntity
{

    public function __construct()
    {
        parent::__construct('app_menu');
    }
    
    /**
     * 
     * @return MenuItem[]
     */
    public function getMainMenu()
    {
        $sql = "select enty.id as entityId,
                	enty.tableName as baseTable,
                    menu.title,
                    menu.url,
                    menu.isBrowser
                from app_menu menu
                left join entity enty on menu.entityId = enty.id
                where menu.active = 1
                order by menu.sequence";
        
        $menus = [];
        foreach (db_select($sql) as $o)
        {
            $item = new MenuItem();
            $item->title = safestr($o->title);
            $item->entityId = intval($o->entityId);
            $item->baseTable = safestr($o->baseTable);
            $item->url = safestr($o->url);
            $item->isBrowser = (intval($o->isBrowser) > 0);
            
            $menus[] = $item;
        }
        
        return $menus;
    }
}

