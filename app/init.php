<?php

use app\model\TextEntity;

error_reporting(E_ALL);

define('LIBRARY_ROOT', APPLICATION_ROOT . 'lib/');
define('CONTROLLER_ROOT', APPLICATION_ROOT . 'controller/');
define('MODEL_ROOT', APPLICATION_ROOT . 'model/');
define('VIEW_ROOT', APPLICATION_ROOT . 'view/');

// Import base libraries
require(LIBRARY_ROOT . 'TextMapper.php');
require(LIBRARY_ROOT . 'http.php');
require(LIBRARY_ROOT . 'func.php');
require(LIBRARY_ROOT . 'db.php');
require(LIBRARY_ROOT . 'DataObject.php');
require(LIBRARY_ROOT . 'DataEntity.php');
require(LIBRARY_ROOT . 'session.php');
require(LIBRARY_ROOT . 'controller.php');

// Import Data Objects
require(MODEL_ROOT . 'objects/UserDataObject.php');

// Import entities
require(MODEL_ROOT . 'SystemEntity.php');
require(MODEL_ROOT . 'TextEntity.php');
require(MODEL_ROOT . 'ServiceEntity.php');
require(MODEL_ROOT . 'UserEntity.php');
require(MODEL_ROOT . 'UserProfileEntity.php');
require(MODEL_ROOT . 'ContactEntity.php');
require(MODEL_ROOT . 'CompanyEntity.php');
require(MODEL_ROOT . 'MenuEntity.php');

// Import higher level business logic
require(MODEL_ROOT . 'bu/MenuManager.php');
require(LIBRARY_ROOT . 'DataGrid.php');
require(LIBRARY_ROOT . 'DataForm.php');

session_init();

function _t($varname)
{
    static $tm;
    if (empty($tm))
    {
        $tm = (new TextEntity())->loadMappings();
    }
    
    return $tm->safe($varname);
}


