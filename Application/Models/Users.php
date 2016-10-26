<?php
class Models_Users extends Cms_Db
{
	protected $_name = 'users';
	protected $_primary = 'uid';
	protected $_dependentTables = array(
            "Models_UsersGroups",
            "Models_UsersPermissions"
        );
        
        /**
         * Metoda autoryzacyjna użytkownika
         * 
         * @param string $username Login użytkownika
         * @param string $password Hasło użytkownika (hashowane md5)
         * @return object Obiekt z danymi uzytkownika
         */
        
        public function authenticate ($username, $password)
        {
            $config = Zend_Registry::get('config');
            
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('u' => 'users'), array('uid', 'username', 'password', 'salt', 'is_deleted', 'is_active', 'last_login'))
                           ->joinLeft(array('ud' => 'users_data'), 'ud.uid = u.uid', array('firstname', 'lastname'))
                           ->where('u.username = ?', $username)
                           ->where('u.password = MD5(CONCAT(?, u.salt))', $password)
                           ->where('u.is_deleted = 0')
                           ->where('u.is_active = 1')
                           ->group('u.uid');

            return $this->fetchRow($select);           
        }
        
        /**
         * Metoda wykonuje siÄ™ po autoryzacji uĹĽytkownika
         * 
         * @param object $user Objekt z danymi uĹĽytkownika
         * @return void
         */
        
        public function preAuthenticate ($user)
        {
            $where = $this->getAdapter()->quoteInto('uid = ?', $user->uid);
            
            $data = array(
                'last_login' => new Zend_Db_Expr('NOW()'),
                'last_active_at' => new Zend_Db_Expr('NOW()')                      
            );
            
            $this->update($data, $where);
        }

        public function getUsers ($options = array())
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('u' => $this->_name, array('*')))
                           ->joinLeft(array('ug' => 'users_groups'), 'u.uid = ug.uid', array('ug.gid'))
                           ->joinLeft(array('ud' => 'users_data'), 'u.uid = ud.uid', array('firstname','lastname','mobile','photo'))
                           ->where('u.is_active = 1')
                           ->where('u.is_deleted = 0')
                           ->group('u.uid');
                           
            if(isset($options['gid']))
            {
                $select->where('ug.gid = ?', $options['gid']);
            }
            
            if(isset($options['uid']))
            {
                if(is_array($options['uid']))
                {
                    $select->where('u.uid IN (?)', $options['uid']);
                }
            }
            
            if(isset($options['limit']))
            {
                $select->limit($options['limit']);
            }
            
            if(isset($options['order']))
            {
                $select->order('u.'.$options['order']);
            }
            
            $results = $this->fetchAll($select);
            
            if($results)
            {
                foreach($results as $result)
                {
                    $users[] = array(
                        'uid'             =>   $result['uid'],
                        'username'        =>   $result['username'],
                        'firstname'       =>   $result['firstname'],
                        'lastname'        =>   $result['lastname'],
                        'mobile'          =>   $result['mobile'],
                        'photo'           =>   ($result['photo'] != null ? json_decode($result['photo'], true) : null),
                        'last_login'      =>   $result['last_login'],
                        'last_active_at'  =>   $result['last_active_at'],
                        'created_at'      =>   $result['created_at'],
                    );
                }
            }
                        
            return (isset($users) ? $users : false);
        }
        
        public function getUser ($options)
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('u' => $this->_name, array('*')))
                           ->joinLeft(array('ug' => 'users_groups'), 'u.uid = ug.uid', array('groups_ids' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT ug.gid)')))
                           ->joinLeft(array('up' => 'users_permissions'), 'u.uid = up.uid', array('permissions' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT up.pid,"-",up.is_allowed)')))
                           ->joinLeft(array('ud' => 'users_data'), 'u.uid = ud.uid', array('firstname','lastname','mobile','photo'))
                           ->where('u.is_active = 1')
                           ->group('u.uid');
                 
            if(isset($options) && is_array($options))
            {
                if(isset($options['uid']))
                {
                    $select->where('u.uid = ?', $options['uid']);
                }
            }
            
            $result = $this->fetchRow($select);

            if($result)
            {
                if($result['permissions'])
                {
                    foreach(explode(',', $result['permissions']) as $permission)
                    {
                        $permission = explode('-', $permission);
                        
                        $permissions[$permission[0]] = $permission[1];
                    }
                }
                
                return array(
                    'uid'         =>   $result['uid'],
                    'gid'         =>   explode(',', $result['groups_ids']),
                    'permissions' =>   (isset($permissions) ? $permissions : null),
                    'username'    =>   $result['username'],
                    'firstname'   =>   $result['firstname'],
                    'lastname'    =>   $result['lastname'],
                    'mobile'      =>   $result['mobile'],
                    'photo'       =>   ($result['photo'] != null ? json_decode($result['photo'], true) : null),
                    'last_login'  =>   $result['last_login'],
                    'created_at'  =>   $result['created_at']
                );
            }
                        
            return array();
        }
   
        public function addUser ($formData)
        {
            $this->getAdapter()->beginTransaction();
            try
            {
                $salt = md5(time());

                $data = array(
                    'username'      => $formData['username'],
                    'password'      => md5(md5($formData['password']).$salt),
                    'salt'          => $salt,
                    'is_active'     => 1,
                    'is_deleted'    => 0,
                    'created_at'    => new Zend_Db_Expr('NOW()')
                );

                $last_id = $this->insert($data);
                
                $usergroupsModels = new Models_UsersGroups;
                
                if($formData['gid'] && is_array($formData['gid']))
                {
                    foreach($formData['gid'] as $group_id)
                    {
                        $usersGroupsData = array(
                            'gid'   =>    $group_id,
                            'uid'   =>    $last_id
                        );
                        
                        $usergroupsModels->insert($usersGroupsData);
                    }
                }

                $additionalUserData = array(
                    'uid'           =>    $last_id,
                    'firstname'     =>    $formData['firstname'],
                    'lastname'      =>    $formData['lastname'],
                    'mobile'        =>    $formData['mobile'],                
                    'photo'         =>    $formData['photo']              
                );

                $userdataModels = new Models_UsersData;
                $userdataModels->insert($additionalUserData);
                
                $cmsSearch = new Cms_Search;
                $cmsSearch->setIndex(Cms_Search_Index::getIndex('Users'));
                
                $fields = array(
                    'username'   =>   $data['username'],   
                    'firstname'  =>   $additionalUserData['firstname'],
                    'lastname'   =>   $additionalUserData['lastname'],
                    'mobile'     =>   $additionalUserData['mobile']
                );

                $cmsSearch->addDocument($fields, $last_id);        

                $userpermissionsModels = new Models_UsersPermissions;
                if($formData['permissions'] != NULL && is_array($formData['permissions']))
                {
                    foreach($formData['permissions'] as $pid => $state)
                    {
                        if($state != '')
                        {
                            $data = array(
                                'uid'           =>    $last_id,
                                'pid'           =>    $pid,
                                'is_allowed'    =>    $state,
                            );

                            $userpermissionsModels->insert($data);
                        }
                    }
                }
                
                $this->getAdapter()->commit();
            }
            catch (Cms_Exception $e)
            {
                $this->getAdapter()->rollBack();
            }
        }

        public function editUser ($formData, $id)
        {
            $this->getAdapter()->beginTransaction();
            try
            {
                $data = array(
                    'username'      => $formData['username'],
                    'is_active'     => 1,
                    'is_deleted'    => 0,
                    'created_at'    => new Zend_Db_Expr('NOW()')
                );
                
                if(isset($formData['password']) && !empty($formData['password']))
                {
                    $salt = md5(time());
                    $data['password'] = md5(md5($formData['password']).$salt);
                    $data['salt'] = $salt;
                }

                $this->update($data, $this->getAdapter()->quoteInto('uid = ?', $id));
                
                $usergroupsModels = new Models_UsersGroups;
                $usergroupsModels->delete($this->getAdapter()->quoteInto('uid = ?', $id));
                
                if($formData['gid'] && is_array($formData['gid']))
                {
                    foreach($formData['gid'] as $group_id)
                    {
                        $usersGroupsData = array(
                            'gid'   =>    $group_id,
                            'uid'   =>    $id
                        );
                        
                        $usergroupsModels->insert($usersGroupsData);
                    }
                }
                
                $additionalUserData = array(
                    'firstname'     =>    $formData['firstname'],
                    'lastname'      =>    $formData['lastname'],
                    'mobile'        =>    $formData['mobile'],                
                    'photo'        =>    $formData['photo']                
                );

                $userdataModels = new Models_UsersData;
                $where = $this->getAdapter()->quoteInto('uid = ?', $id);

                if($userdataModels->fetchRow($where))
                {
                    $userdataModels->update($additionalUserData, $where);
                }
                else
                {
                    $additionalUserData['uid'] = $id;                   
                    $userdataModels->insert($additionalUserData);
                }

                $cmsSearch = new Cms_Search;
                $cmsSearch->setIndex(Cms_Search_Index::getIndex('Users'));
                $cmsSearch->deleteDocument($id);
            
                $fields = array(
                    'username'   =>   $data['username'],   
                    'firstname'  =>   $additionalUserData['firstname'],
                    'lastname'   =>   $additionalUserData['lastname'],
                    'mobile'     =>   $additionalUserData['mobile']
                );

                $cmsSearch->addDocument($fields, $id);
                                
                $userpermissionsModels = new Models_UsersPermissions;
                $userpermissionsModels->delete($this->getAdapter()->quoteInto('uid = ?', $id));

                if($formData['permissions'] != NULL && is_array($formData['permissions']))
                {
                    foreach($formData['permissions'] as $permission_id => $state)
                    {
                        if($state != '')
                        {
                            $data = array(
                                'uid'           =>    $id,
                                'pid'           =>    $permission_id,
                                'is_allowed'    =>    $state,
                            );

                            $userpermissionsModels->insert($data);
                        }
                    }
                }

                $this->getAdapter()->commit();
            }
            catch (Cms_Exception $e)
            {
                $this->getAdapter()->rollBack();
            }
        }
        
        public function deleteUsers ($users)
        {
            $this->getAdapter()->beginTransaction();
            try
            {
                $cmsSearch  = new Cms_Search();
                $cmsSearch->setIndex(Cms_Search_Index::getIndex('Users'));
                
                if(is_array($users))
                {
                    $where = $this->getAdapter()->quoteInto('uid IN(?)', $users);
                }
                else
                {
                    $where = $this->getAdapter()->quoteInto('uid = ?', $users);
                }
                                
                $this->delete($where);
                
                $cmsSearch->deleteDocument($users);
                
                $this->getAdapter()->commit();
            }
            catch (Cms_Exception $e)
            {
                $this->getAdapter()->rollBack();
            }
        }

        public function getUsersForPermissions ()
        {
            $results = $this->getUsers();
            
            if($results)
            {
                foreach ($results as $user)
                {
                    $users[$user['uid']] = array(
                        'title'  =>   'ID: '. $user['uid'] .' - '. $user['firstname'] .' '. $user['lastname'] .' - '. $user['username'],
                        'value'  =>   $user['uid'],
                        'id'     =>   $user['uid']
                    );
                }
            }
            
            return (isset($users) ? $users : array());
        }
        

        public function editProfile ($formData, $id)
        {
            $this->getAdapter()->beginTransaction();
            try
            {
                if(isset($formData['password']) && !empty($formData['password']))
                {
                    $salt = md5(time());
                    $data['password'] = md5(md5($formData['password']).$salt);
                    $data['salt'] = $salt;
                    $this->update($data, $this->getAdapter()->quoteInto('uid = ?', $id));               
                }
                
                $additionalUserData = array(
                    'firstname'     =>    $formData['firstname'],
                    'lastname'      =>    $formData['lastname'],
                    'mobile'        =>    $formData['mobile'],                
                    'photo'         =>    $formData['photo']                
                );

                $userdataModels = new Models_UsersData;
                $where = $this->getAdapter()->quoteInto('uid = ?', $id);

                if($userdataModels->fetchRow($where))
                {
                    $userdataModels->update($additionalUserData, $where);
                }
                else
                {
                    $additionalUserData['uid'] = $id;                   
                    $userdataModels->insert($additionalUserData);
                }

                $cmsSearch = new Cms_Search;
                $cmsSearch->setIndex(Cms_Search_Index::getIndex('Users'));
                $cmsSearch->deleteDocument($id);
            
                $fields = array(
                    'username'   =>   $data['username'],   
                    'firstname'  =>   $additionalUserData['firstname'],
                    'lastname'   =>   $additionalUserData['lastname'],
                    'mobile'     =>   $additionalUserData['mobile']
                );

                $cmsSearch->addDocument($fields, $id);         

                $this->getAdapter()->commit();
            }
            catch (Cms_Exception $e)
            {
                $this->getAdapter()->rollBack();
            }
        }
}

?>
