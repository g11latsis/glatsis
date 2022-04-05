<?php

function login()
{
    try
    {
        $username = http_post_string('username');
        $password = http_post_string('password');
        
        if (empty($username) || empty($password))
        {
            AjaxResponse::error('Παρακαλούμε εισάγετε όνομα και κωδικό χρήστη')->send();
        }
        
        $entity = new UserEntity();
        $user = $entity->authenticate($username, $password);
        
        if ($user === null)
        {
            AjaxResponse::error('Άκυρο όνομα ή/και κωδικός χρήστη')->send();
        }
        
        session_login($user);
        
        AjaxResponse::success()->send();
    }
    catch (Exception $e)
    {
        AjaxResponse::error($e->getMessage())->send();
    }
}

function logout()
{
    session_logout();
    redirect_base();
}