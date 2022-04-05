<?php
namespace app\model;

class UserProfileEntity extends \DataEntity
{
    public function __construct()
    {
        parent::__construct('user_profile');
    }
    
    public function getEntityPermissions($profileId)
    {
        $sql = "select entityId, canView, canCreate, canUpdate, canDelete
                from uspr_entity 
                where profileId = :profileId";
        
        return db_select($sql, [':profileId' => $profileId], 'entityId');
    }
}

