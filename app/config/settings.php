<?php

function config_settings()
{
    static $settings = [
        
        'connections' => [
            'system' => [
                'dsn' => 'mysql:host=localhost;port=3306;dbname=samarites',
                'username' => 'root',
                'password' => 'Winter2022!',
                'charset'  => 'utf8',
                'timeout'  => 0
            ]
        ]
        
    ];
    
    return $settings;
}