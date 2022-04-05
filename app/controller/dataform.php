<?php

function edit()
{
    $entityId = http_get_int('eid');
    $recordId = http_get_int('id');
    
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
    
    // Create dataform from json
    $jsonfile = $entityObject->getDataInfo();
    if (!file_exists($jsonfile))
    {
        redirect_404();
    }
    
    $dataform = new DataForm($entityObject, $recordId, $jsonfile);
    $data = [
        'form' => $dataform,
        'eid' => $entityId,
        'id'  => $recordId
    ];
    
    load_view('dataform', $data);
}

function save()
{
    try
    {
        $entityId = http_get_int('eid');
        $recordId = http_get_int('id');
            
        $oEntity = (new SystemEntity())->getById($entityId);
        if (empty($oEntity))
        {
            throw new Exception('Undefined entity');
        }
        
        // Create the entity object
        $entityClass = $oEntity->implementer;
        if (empty($entityClass))
        {
            redirect_404();
        }
        $entityObject = new $entityClass();
        
        // Create dataform from json
        $jsonfile = $entityObject->getDataInfo();
        if (!file_exists($jsonfile))
        {
            redirect_404();
        }
        
        $dataform = new DataForm($entityObject, $recordId, $jsonfile);
        $dataform->load($jsonfile);
        $dataform->collectPosted();
        
        $mandatory = $dataform->checkEmptyFields();
        if (!empty($mandatory))
        {
            AjaxResponse::error(_t('form.mandatoryFields'), $mandatory, 406)->send();
        }
        
        $nonUnique = $dataform->checkUniqueFields();
        if (!empty($nonUnique))
        {
            AjaxResponse::error(_t('form.uniquenessViolation'), $nonUnique, 409)->send();
        }
        
        $newid = $dataform->save();
        $data = [
            'newid' => ($dataform->isNew() ? $newid : null),
            'title' => $dataform->getViewFieldValue()
        ];
        AjaxResponse::success($data, _t('save.success'))->send();
    }
    catch (Exception $x)
    {
        AjaxResponse::error($x->getMessage())->send();
    }
}

function delete()
{
    try
    {
        $entityId = http_get_int('eid');
        $recordId = http_get_int('id');
        
        $oEntity = (new SystemEntity())->getById($entityId);
        if (empty($oEntity))
        {
            throw new Exception('Undefined entity');
        }
        
        // Create the entity object
        $entityClass = $oEntity->implementer;
        if (empty($entityClass))
        {
            redirect_404();
        }
        
        $entityObject = new $entityClass();
        $entityObject->deleteById($recordId);
        AjaxResponse::success(null, _t('delete.success'))->send();
    }
    catch (Exception $x)
    {
        AjaxResponse::error($x->getMessage())->send();
    }
}
