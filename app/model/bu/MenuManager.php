<?php

use app\model\UserProfileEntity;

class MenuManager
{
    public function loadUserMenu(UserViewObject $user)
    {
        $allMenus = (new MenuEntity())->getMainMenu();
        if ($user->superuser)
        {
            return $allMenus;
        }
        
        $entityPerms = (new UserProfileEntity())->getEntityPermissions($user->profileId);
        
        $userMenus = [];
        foreach ($allMenus as $menu)
        {
            if (empty($menu->entityId))
            {
                $userMenus[] = $menu;
            }
            else
            {
                if (!empty($entityPerms[$menu->entityId]['canView']))
                {
                    $userMenus[] = $menu;
                }
            }
        }
        
        return $userMenus;
    }
}

