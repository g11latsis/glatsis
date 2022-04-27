<?php

function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function load_settings($path, $config_file = 'settings')
{
    require_once(APPLICATION_ROOT . 'config/' . $config_file . '.php');
    $settings = config_settings();
    
    $parts = explode('/', $path);
    $c = count($parts);
    
    for ($i = 0; $i < $c; $i++)
    {
        $settings = $settings[$parts[$i]];
    }
    
    return $settings;
}

function load_view($name, array $data = [])
{
    require(VIEW_ROOT . $name . '.php');
}

function url($controller, $action, array $params = [])
{
    $url = "index.php?c=$controller&a=$action";
    foreach ($params as $key => $val)
    {
        $url .= "&$key=$val";
    }
    
    return $url;
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function redirect_base()
{
    redirect('/');
}

function redirect_404()
{
    redirect(url('default', 'not_found_404'));
}

 function safestr($str)
{
    return  htmlentities ($str ?  : '' , ENT_QUOTES);  
}

function format_db_date($dateStr, $format)
{
    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateStr);
    return ($date != null) ? $date->format($format) : '';
}

function load_lookups()
{
    static $defs;
    if (empty($defs))
    {
        $conf = APPLICATION_ROOT . 'config/lookups.json';
        if (is_file($conf))
        {
            $defs = json_decode(file_get_contents($conf));
        }
    }
    
    return $defs;
}
