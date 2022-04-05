<?php

function session_init()
{
    session_name('samarites_session');
    session_start([
        'cookie_lifetime' => 0,
        'cookie_httponly' => true,
        'cookie_secure' => false,
        'cookie_samesite' => true,
        'use_strict_mode' => true,
        'use_cookies' => true,
        'use_only_cookies' => true,
        'use_trans_sid' => false
    ]);
}

function session_login(UserViewObject $user)
{
    session_regenerate_id();
    $_SESSION['user'] = $user;
}

function is_logged_in()
{
    return !empty($_SESSION['user']);
}

function session_logout()
{
    unset($_SESSION['user']);
    session_destroy();
}

/**
 * 
 * @return NULL|UserViewObject
 */
function session_get_user()
{
    return is_logged_in() ? $_SESSION['user'] : null;
}