<?php

function auth_controller()
{
    if (!is_logged_in())
    {
        redirect_base();
        exit;
    }
    
    return [
        'menu' => (new MenuManager())->loadUserMenu(session_get_user())
    ];
}