<?php

function index()
{
    load_view('login');
}

function not_found_404()
{
    echo 'Not found!';
}

function screen_main()
{    
    $data = auth_controller();    
    load_view('main', $data);        
}