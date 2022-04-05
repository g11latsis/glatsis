<?php


class UserEntity extends \DataEntity implements IBrowsable
{
    public function __construct()
    {
        parent::__construct('user');
    }
    
    public function getDescriptor()
    {
        return APPLICATION_ROOT . 'config/browser/user.json';
    }
    
    public function getDataInfo()
    {
        return APPLICATION_ROOT . 'config/form/user.json';
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return NULL|UserViewObject
     */
    public function authenticate($username, $password)
    {
        $sql = "select user.id as userId,
                	user.username,
                    user.password,
                    uspr.id as profileId,
                    uspr.isSuper as superuser,
                    uspr.level as userLevel,
                    cont.id as contactId,
                    cont.code as contactCode,
                    cont.firstname,
                    cont.lastname
                from user 
                inner join contact cont on user.contactId = cont.id
                inner join user_profile uspr on user.profileId = uspr.id
                where user.username = :username
                  and user.active = 1
                  and uspr.active = 1";
        
        
        $o = db_select_single($sql, [
            'username' => $username
        ]);
        
        if (empty($o))
        {
            return null;
        }
        
        if (!password_verify($password, $o->password))
        {
            return null;
        }
        
        $user = new UserViewObject();
        $user->id = intval($o->userId);
        $user->profileId = intval($o->profileId);
        $user->contactId = intval($o->contactId);
        $user->username = safestr($o->username);
        $user->code = safestr($o->contactCode);
        $user->firstname = safestr($o->firstname);
        $user->lastname = safestr($o->lastname);
        $user->superuser = ($o->superuser > 0);
        $user->level = intval($o->userLevel);
        
        
        return $user;
    }
}

