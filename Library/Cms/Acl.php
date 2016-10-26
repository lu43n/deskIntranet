<?php
/**
 * deskCMS
 * 
 * @copyright Copyright (c) 2012
 * @version 1.0
 * @author deskCMS Team
 * @see http://deskcms.pl/
 * 
 */

class Cms_Acl extends Zend_Acl
{
    protected $_permissions = array();
    
    public function addPermissions ()
    {
        // Add permissions (resources)
        
        $permissionsModels = new Models_Permissions();
        $permissions = $permissionsModels->getPermissions(array('order' => 'depth ASC'));
        
        if($permissions)
        {
            foreach($permissions as $permission)
            {
                if($permission['parent_id'] !== null)
                {
                    $this->addResource($permission['slug'], $permissions[$permission['parent_id']]['slug']);
                }
                else
                {
                    $this->addResource($permission['slug']);
                }
            }
        }
                
        return $this;
    }


    public function addGroups ()
    {
        // Add users associated with groups ( roles )
        
        $groupsModels = new Models_Groups();        
        $this->_groups = $groupsModels->getGroupsForACL();
        
        if($this->_groups)
        {
            foreach($this->_groups as $group)
            {
                // Add role Groups
                        
                if($group['parent_id'] !== null)
                {
                    $this->addRole('group-'.$group['slug'], 'group-'. $this->_groups[$group['parent_id']]['slug']);
                }
                else
                {
                    $this->addRole('group-'.$group['slug']);
                }
            }
        }
        

        return $this;
    }

    public function addUsers ()
    {
        // Add role Users

        $usersGroupsModels = new Models_UsersGroups;
        $users = $usersGroupsModels->getUsersForACL();
                      
        if($users)
        {
            foreach($users as $user)
            {
                $user_groups = explode(',', $user['groups_ids']);

                foreach($user_groups as $key => $user_group)
                {
                    $user_groups[$key] = 'group-'.$this->_groups[$user_group]['slug'];
                }
                $this->addRole('user-'.$user['uid'], $user_groups);
            }
        }

        return $this;
    }

    public function addUserPermissions ()
    {
        // Associate permissions to user

        $usersPermissionsModels = new Models_UsersPermissions;
        $usersPermissions = $usersPermissionsModels->getUsersPermissions();
        
        if($usersPermissions)
        {
            foreach($usersPermissions as $usersPermission)
            {
                if($usersPermission['is_allowed'] == 1)
                {
                    $this->allow('user-'.$usersPermission['uid'], $usersPermission['permission_slug']);
                }
                else
                {
                    $this->deny('user-'.$usersPermission['uid'], $usersPermission['permission_slug']);
                }
            }
        }
                
        return $this;
    }

    public function addGroupPermissions ()
    {
        $groupsPermissionsModels = new Models_GroupsPermissions;
        $groupsPermissions = $groupsPermissionsModels->getGroupsPermissions();
        
        if($groupsPermissions)
        {
            foreach($groupsPermissions as $groupsPermission)
            {
                if($groupsPermission['is_allowed'] == 1)
                {
                    $this->allow('group-'.$groupsPermission['group_slug'], $groupsPermission['permission_slug']);
                }
                else
                {
                    $this->deny('group-'.$groupsPermission['group_slug'], $groupsPermission['permission_slug']);
                }                    

            }
        }
        
        return $this;
    }

    public function isAllowed ($role = null, $resource = null)
    {
        $logger = Zend_Registry::get('logger');
        
        try
        {
            return parent::isAllowed($role, strtolower($resource));
        }
        catch(Exception $e)
        {
            $logger->log('Unknown resource or role. (Resorurce: '.$resource.', Role: '.$role.')', Zend_Log::ALERT);
            return false;
        }
    }

    public function isUserAllowed ($resource = null)
    {
        $auth = Zend_Auth::getInstance();

        if($auth->hasIdentity() && $this->hasRole('user-'.$auth->getIdentity()->uid))
        {
            if($this->isAllowed('user-'.$auth->getIdentity()->uid, $resource))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

?>
