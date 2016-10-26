<?php
class Models_Permissions extends Cms_Db 
{
    protected $_name = 'permissions';
    protected $_primary = 'pid';
    protected $_dependentTables = array(
        "Models_Permissions",
        "Models_GroupsPermissions",
        "Models_UsersPermissions"
    );
    protected $_referenceMap = array(
        "Permissions" => array(
            "columns" => array("parent_id"),
            "refTableClass" => "Models_Permissions",
            "refColumns" => array("pid")
        )
    );

    public function getPermissions ($options = false, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('p' => $this->_name))
                       ->joinLeft(array('p2' => 'permissions'), 'p2.parent_id = p.pid', array('count_permissions' => 'COUNT(DISTINCT p2.pid)'))
                       ->joinLeft(array('pt' => 'permissions_translation'), 'pt.pid = p.pid AND pt.lid = "'.$language.'"', array('permission_title' => 'pt.title'))
                       ->group('p.pid');

        if(isset($options) && is_array($options))
        {
            if(isset($options['pid']))
            {
                if(is_array($options['pid']))
                {
                    $select->where('p.pid IN (?)', $options['pid']);
                }
            }
            
            if(isset($options['name']))
            {
                if(is_array($options['name']))
                {
                    $select->where('p.name IN (?)', $options['name']);
                }
                else
                {
                    $select->where('p.name = ?', $options['name']);
                }
            }
            
            if(isset($options['order']))
            {
                $select->order('p.'.$options['order']);
            }

            if(isset($options['parent_id']))
            {
                $select->where('p.parent_id = ?', $options['parent_id']);
            }

            if(isset($options['depth']))
            {
                $select->where('p.depth = ?', $options['depth']);
            }
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $permissions[$result['pid']] = array(
                    'pid'                =>   $result['pid'],
                    'name'               =>   $result['name'],
                    'depth'              =>   $result['depth'],
                    'slug'               =>   $result['slug'],
                    'path'               =>   $result['path'],
                    'parent_id'          =>   $result['parent_id'],
                    'translation'        =>   array(
                        'title'     =>   $result['permission_title'],
                    ),
                    'count_permissions'  =>   $result['count_permissions']
                );
            }
        }

        return (isset($permissions) ? $permissions : false);
    }

    public function getPermission ($options, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('p' => $this->_name))
                       ->joinLeft(array('pt' => 'permissions_translation'), 'pt.pid = p.pid AND pt.lid = "'.$language.'"', array('permission_title' => 'pt.title'))
                       ->joinLeft(array('gp' => 'groups_permissions'), 'p.pid = gp.pid', array('permissions_groups' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT gp.gid,"-",gp.is_allowed)')))
                       ->joinLeft(array('up' => 'users_permissions'), 'p.pid = up.pid', array('permissions_users' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT up.uid,"-",up.is_allowed)')));

        if(isset($options) && is_array($options))
        {
            if(isset($options['pid']))
            {
                $select->where('p.pid = ?', $options['pid']);
            }
        }

        $result = $this->fetchRow($select);

        if($result)
        {
            $result = $result->toArray();

            if($result['permissions_groups'])
            {
                foreach(explode(',', $result['permissions_groups']) as $permission)
                {
                    $permission = explode('-', $permission);

                    $permissions_groups[$permission[0]] = $permission[1];
                }
            } 

            if($result['permissions_users'])
            {
                foreach(explode(',', $result['permissions_users']) as $permission)
                {
                    $permission = explode('-', $permission);

                    $permissions_users[$permission[0]] = $permission[1];
                }
            }   

            $permissions = array(
                'pid'         =>   $result['pid'],
                'name'        =>   $result['name'],
                'depth'       =>   $result['depth'],
                'translation' =>   array(
                    'title'       =>   $result['permission_title'],
                ),
                'slug'        =>   $result['slug'],
                'path'        =>   $result['path'],
                'parent_id'   =>   $result['parent_id'],
                'groups'      =>   (isset($permissions_groups) ? $permissions_groups : array()),
                'users'       =>   (isset($permissions_users) ? $permissions_users : array())
            );
        }

        return (isset($permissions) ? $permissions : false);
    }

    public function getPermissionsTree ($options = array(), $language = LOCALE_ID)
    {        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('p' => $this->_name))
                       ->joinLeft(array('pt' => 'permissions_translation'), 'pt.pid = p.pid AND pt.lid = "'.$language.'"', array('permission_title' => 'pt.title'));

        if(isset($options['exclude_childrens']))
        {
            if(isset($options['exclude_childrens']['pid']))
            {
                $permission = $this->getPermission(array('pid' => $options['exclude_childrens']['pid']));
                
                $path = $permission['path'];
            }
            elseif(isset($options['exclude_childrens']['path']))
            {
                $path = $options['exclude_childrens']['path'];
            }
            
            $select->where('p.path NOT LIKE ?', $path);
        }
        
        $results = $this->fetchAll($select);
        
        $permissions = array();
        if($results)
        {
            if(!isset($options['exclude_root']) || $options['exclude_root'] == false)
            {
                $permissions[0][0] = array(
                    'pid'         =>   0,
                    'title'       =>   'Kategoria główna',
                    'depth'       =>   0,
                    'parent_id'   =>   null,
                    'value'       =>   0
                );
            }
            
            foreach($results as $result)
            {
                
                $permissions[($result['parent_id'] == NULL ? 0 : $result['parent_id'])][$result['pid']] = array(
                    'pid'         =>   $result['pid'],
                    'name'        =>   $result['name'],
                    'depth'       =>   $result['depth'],
                    'slug'        =>   $result['slug'],
                    'path'        =>   $result['path'],
                    'parent_id'   =>   $result['parent_id'],

                    'id'          =>   $result['pid'],
                    'title'       =>   $result['permission_title'],
                    'value'       =>   $result['pid'],
                );
            }
        }

        return $permissions;
    }


    public function getPermissionsForACL ()
    {
        $where = $this->getAdapter()->quoteInto('did = ?', CMS_DOMAIN_ID);

        return $this->fetchAll($where, 'depth ASC');
    }

    public function addPermission ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'did'        => CMS_DOMAIN_ID,
                'name'       => str_replace('-', '_', $formData['name']),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null)
            );
            
            if($this->isPermissionUnique($data['name'], $data['parent_id']) == false)
            {
                throw new Cms_Form_Exception('Nazwa uprawnienia musi być unikalna.');
            }

            $last_id = $this->insert($data);

            $parentPermission = $this->getPermission(array('pid' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $permissionsAddtionalData = array(
                    'path'      => ($parentPermission['path'].'.'.$last_id),
                    'slug'      => ($parentPermission['slug'].'-'.str_replace('-', '_', $formData['name'])),
                    'depth'     => ($parentPermission['depth'] + 1)
                    );
            }
            else
            {
                $permissionsAddtionalData = array(
                    'path'      => $last_id,
                    'slug'      => str_replace('-', '_', $formData['name']),
                    'depth'     => 1
                    );
            }

            $this->update($permissionsAddtionalData, $this->getAdapter()->quoteInto('pid = ?', $last_id));

            $groupsPermissionsModels = new Models_GroupsPermissions;
            if(is_array($formData['groups']))
            {
                foreach($formData['groups'] as $gid => $value)
                {
                    if($value != '')
                    {
                        $groupsPermissionsModels->insert(array(
                            'gid'           =>    $gid,
                            'pid'           =>    $last_id,
                            'is_allowed'    =>    $value
                        ));
                    }
                }
            }

            $usersPermissionsModels = new Models_UsersPermissions;
            if(is_array($formData['users']))
            {
                foreach($formData['users'] as $uid => $value)
                {
                    if($value != '')
                    {
                        $usersPermissionsModels->insert(array(
                            'uid'           =>    $uid,
                            'pid'           =>    $last_id,
                            'is_allowed'    =>    $value
                        ));
                    }
                }
            }

            $permissionsTranslationModels = new Models_PermissionsTranslation;
            $languagesModels              = new Models_Languages;

            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Permissions'));
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $permissionsTranslationData = $formData['languages'][$language['code']];
                    $permissionsTranslationData['pid']  = $last_id;
                    $permissionsTranslationData['lid']  = $language['lid'];

                    $permissionsTranslationModels->insert($permissionsTranslationData);
     
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $permissionsTranslationData['title'],
                    );
                    
                    $cmsSearch->addDocument($fields, $last_id);
                }
            }            

            $this->getAdapter()->commit();

        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }

    public function editPermission ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'name'       => str_replace('-', '_', $formData['name']),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null),
                'path'       => $formData['path'],
                'slug'       => $formData['slug'],
                'depth'      => $formData['depth']
            );
            
            if($this->isPermissionUnique($data['name'], $data['parent_id'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa uprawnienia musi być unikalna.');
            }

            $oldPath = $data['path'];
            $oldSlug = $data['slug'];
            $parentPermission = $this->getPermission(array('pid' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $data['path'] = $parentPermission['path'].'.'.$id;
                $data['slug'] = $parentPermission['slug'].'-'.$data['name'];
                $data['depth'] = ($parentPermission['depth'] + 1);
            }
            else
            {
                $data['path'] = $id;
                $data['slug'] = str_replace('-', '_', $formData['name']);
                $data['depth'] = 1;
            }

            $where = $this->getAdapter()->quoteInto('pid = ?', $id);
            $this->update($data, $where);

            if($data['depth'] > ($data['parent_id'] == null ? 0 : $parentPermission['depth']))
            {
                    $setDepth = 'depth-'.($data['depth'] - ($data['parent_id'] == null ? 0 : $parentPermission['depth']) - 1);
            }
            elseif($data['depth'] == ($data['parent_id'] == null ? 0 : $parentPermission['depth']))
            {
                    $setDepth = 'depth+1';
            }
            else
            {
                    $setDepth = 'depth+'.(($data['parent_id'] == null ? 0 : $parentPermission['depth']) - $data['depth'] + 1);
            }

            $permissionsAdditionalData = array(
                'path' => new Zend_Db_Expr('REPLACE(path, "'.$oldPath.'","'.$data['path'].'")'),
                'slug' => new Zend_Db_Expr('REPLACE(slug, "'.$oldSlug.'","'.$data['slug'].'")'),
                'depth' => new Zend_Db_Expr($setDepth)
            );

            $where = array(
                $this->getAdapter()->quoteInto('path LIKE(?)', $oldPath.'%'),
                $this->getAdapter()->quoteInto('pid <> ?', $id)
            );

            $this->update($permissionsAdditionalData, $where);

            // Zapis uprawnień dla grup i użytkowników

            $groupsPermissionsModels = new Models_GroupsPermissions;

            $where = $this->getAdapter()->quoteInto('pid = ?', $id);                
            $groupsPermissionsModels->delete($where);

            if(is_array($formData['groups']))
            {                
                foreach($formData['groups'] as $gid => $value)
                {
                    if($value != '')
                    {
                        $groupsPermissionsModels->insert(array(
                            'gid'           =>    $gid,
                            'pid'           =>    $id,
                            'is_allowed'    =>    $value
                        ));
                    }
                }
            }

            $usersPermissionsModels = new Models_UsersPermissions;

            $where = $this->getAdapter()->quoteInto('pid = ?', $id);                
            $usersPermissionsModels->delete($where);

            if(is_array($formData['users']))
            {
                foreach($formData['users'] as $uid => $value)
                {
                    if($value != '')
                    {
                        $usersPermissionsModels->insert(array(
                            'uid'           =>    $uid,
                            'pid'           =>    $id,
                            'is_allowed'    =>    $value
                        ));
                    }
                }
            }

            // Zapisywanie translacji

            $permissionsTranslationModels = new Models_PermissionsTranslation;
            $languagesModels              = new Models_Languages;

            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Permissions'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $permissionsTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('pid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($permissionsTranslationModels->fetchRow($where))
                    {
                        $permissionsTranslationModels->update($permissionsTranslationData, $where);
                    }
                    else
                    {
                        $permissionsTranslationData['lid']  = $language['lid'];
                        $permissionsTranslationData['pid']  = $id;
                        $permissionsTranslationModels->insert($permissionsTranslationData);
                    }                   
                    
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $permissionsTranslationData['title'],
                        'description'    =>   $permissionsTranslationData['description']
                    );
                    
                    $cmsSearch->addDocument($fields, $id);
                }
            }

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }

    public function deletePermissions ($permissions)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearch  = new Cms_Search();
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Permissions'));
            
            if(is_array($permissions))
            {
                $where = $this->getAdapter()->quoteInto('pid IN(?)', $permissions);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('pid = ?', $permissions);
            }
            
            $this->delete($where);
            
            $cmsSearch->deleteDocument($permissions);

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    
    /*
     * Sprawdzenie czy nazwa uprawnienia jest unikalna
     */
    
    public function isPermissionUnique ($name, $parent_id, $id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('p' => $this->_name))
                       ->where('p.name = ?', $name);

        if($id != null)
        {
            $select->where('p.pid <> ?', $id);
        }
        
        if($parent_id == null)
        {
            $select->where('p.parent_id IS NULL');
        }
        else 
        {
            $select->where('p.parent_id = ?', $parent_id);
        }

        if($this->fetchAll($select)->count() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }     

    public function getBreadcrumbs ($id, $language = LOCALE_ID)
    {            
        if($id)
        {              
            $permission = $this->getPermission($id);

            $path = explode('.', $permission['path']);

            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('p' => $this->_name))
                           ->joinLeft(array('pt' => 'permissions_translation'), 'pt.pid = p.pid AND pt.lid = "'.$language.'"', array('permission_title' => 'pt.title'))
                           ->where('p.pid IN (?)', $path)
                           ->order('p.depth DESC');

            $results = $this->fetchAll($select);

            if($results)
            {   
                $permissions = array();

                foreach($results as $result)
                {
                    $permissions[$result['pid']] = array(
                        'pid'                =>   $result['pid'],
                        'name'               =>   $result['name'],
                        'depth'              =>   $result['depth'],
                        'title'              =>   $result['permission_title'],
                        'slug'               =>   $result['slug'],
                        'path'               =>   $result['path'],
                        'parent_id'          =>   $result['parent_id']
                    );  
                }
            }        
        }

        return (isset($permissions) ? $permissions : false);
    }
}
?>
