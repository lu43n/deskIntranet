<?php
class Models_Employees
{

    public function getEmployees ()
    {
        $usersModels = new Models_Users;
        $groupsModels = new Models_Groups;
        
        $groups = $groupsModels->getGroups();
        
        $structure = array();
        if($groups)
        {
            foreach($groups as $group)
            {
                $structure[($group['parent_id'] == NULL ? 0 : $group['parent_id'])][$group['gid']] = array(
                    'gid'   => $group['gid'],
                    'title' => $group['translation']['title'],
                    'users' => $usersModels->getUsers(array('gid' => $group['gid']))
                );
            }
        }

        return $structure;
    }
   
}
?>
