<?php

function browser_list()
{
    $data = auth_controller();
    $data['view'] = 'browser';
    
    // Find the Entity from DB
    $entityId = http_get_int('eid');
    $oEntity = (new SystemEntity())->getById($entityId);
    if (empty($oEntity))
    {
        redirect_404();
    }
    
    // Create the entity object
    $entityClass = $oEntity->implementer;
    if (empty($entityClass))
    {
        redirect_404();
    }
    $entityObject = new $entityClass();
    
    // Create datagrid from json    
    $jsonfile = $entityObject->getDescriptor();
    if (!file_exists($jsonfile))
    {
        redirect_404();
    }
    
    $datagrid = new DataGrid();
    $datagrid->load($jsonfile);
    
    $data['eid'] = $entityId;
    $data['datagrid'] = $datagrid;
    load_view('main', $data);
}


function browser_data()
{
    $data = auth_controller();
    $data['view'] = 'browser';
    
    // Find the ntity from DB
    $entityId = http_get_int('eid');
    $oEntity = (new SystemEntity())->getById($entityId);
    if (empty($oEntity))
    {
        redirect_404();
    }
    
    // Create the entity object
    $entityClass = $oEntity->implementer;
    if (empty($entityClass))
    {
        redirect_404();
    }
    
    $entityObject = new $entityClass();
    $jsonfile = $entityObject->getDescriptor();
    if (!file_exists($jsonfile))
    {
        redirect_404();
    }
    
    $datagrid = new DataGrid();
    $datagrid->load($jsonfile);
    
    // Setup Paging
    $pageNum = http_get_int('page', 1);
    if ($pageNum <= 0)
    {
        $pageNum = 1;
    }
    
    $rowsPerPage = http_get_int('rows', DataGrid::DefaultPageSize);
    if ($rowsPerPage <= 0)
    {
        $rowsPerPage = DataGrid::DefaultPageSize;
    }
    $offset = ($pageNum - 1) * $rowsPerPage;
    
    $options = new QueryOptions();
    $options->setLimits($offset, $rowsPerPage);
    
    // Setup sorting
    $sortings = http_get_string('sort');
    if (!empty($sortings))
    {        
        $sortFields = explode(',', $sortings);
        $orderings = explode(',', http_get_string('order'));
        
        for ($i = 0; $i < count($sortFields); $i++)
        {
            $options->addSorting($sortFields[$i], $orderings[$i] == 'asc');
        }        
    }
    
    // Setup filtering
    $filterStr = http_get_string('filters');
    if (!empty($filterStr))
    {
        foreach (json_decode($filterStr) as $f)
        {
            $options->addFilter($f->name, $f->value, $f->operator);
        }
    }
    
    $data = $datagrid->getData($entityObject, $options);
    
    echo \json_encode($data);
    exit;
    
}


