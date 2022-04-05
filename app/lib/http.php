<?php

function http_get_string($varname, $default = '')
{
    return (isset($_GET[$varname]) ? trim($_GET[$varname]) : strval($default));
}

function http_get_int($varname, $default = 0)
{
    return (isset($_GET[$varname]) ? intval($_GET[$varname]) : intval($default));
}

function http_post_string($varname, $default = '')
{
    return (isset($_POST[$varname]) ? trim($_POST[$varname]) : strval($default));
}

function http_post_int($varname, $default = 0)
{
    return (isset($_POST[$varname]) ? intval($_POST[$varname]) : intval($default));
}

function http_post_bool($varname, $default = false)
{
    if (isset($_POST[$varname])) 
    {
        if (trim(strval($_POST[$varname])) === '')
        {
            return true;
        }
        
        return boolval($_POST[$varname]);
    }
    
    return boolval($default);
}

/*function redirect_404()
{
    require_once(CONTROLLER_ROOT . 'default.php');
    not_found_404();
    exit;
}*/

function route_request()
{
    $controller = http_get_string('c');
    if ($controller === '')
    {
        $controller = 'default';
    }
    
    $action = http_get_string('a');
    if ($action === '')
    {
        $action = 'index';
    }
    
    $controller_path = CONTROLLER_ROOT . $controller . '.php';
    if (!is_file($controller_path))
    {
        redirect_404();
    }
    
    require($controller_path);
    if (!function_exists($action))
    {
        redirect_404();
    }
    
    call_user_func($action);
}

class AjaxResponse
{
    public $status;
    public $message;
    public $data;
    
    public static function success($data = null, $message = '')
    {
        $response = new AjaxResponse();
        $response->status = 200;
        $response->message = $message;
        $response->data = $data;
        
        return $response;
    }
    
    public static function error($message, $data = null, $status = 400)
    {
        $response = new AjaxResponse();
        $response->status = $status;
        $response->message = $message;
        $response->data = $data;
        
        return $response;
    }
    
    public function send()
    {
        echo \json_encode($this);
        exit;
    }
}


