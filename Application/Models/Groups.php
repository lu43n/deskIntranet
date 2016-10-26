<?php
class Models_Groups extends Cms_Db
{
    protected $_name = 'groups';
    protected $_primary = 'gid';
    protected $_dependentTables = array(
        "Models_GroupsPermissions",
        "Models_Groups"
    );
    protected $_referenceMap = array(
        "Groups" => array(
            "columns" => array("gid"),
            "refTableClass" => "Models_Groups",
            "refColumns" => array("gid")
        )
    );
    
    public function getGroups ($options = array(), $language = LOCALE_ID)
    {        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name), array('*', 'group_id' => 'g.gid'))
                       ->joinLeft(array('gt' => 'groups_translation'), 'gt.gid = g.gid AND gt.lid = "'.$language.'"', array('group_title' => 'gt.title'))
                       ->joinLeft(array('g2' => 'groups'), 'g2.parent_id = g.gid AND g2.is_deleted = 0', array('count_groups' => 'COUNT(DISTINCT g2.gid)'))
                       ->joinLeft(array('ug' => 'users_groups'), 'ug.gid = g.gid')
                       ->joinLeft(array('u' => 'users'), 'u.uid = ug.uid AND u.is_deleted = 0', array('count_users' => 'COUNT(DISTINCT u.uid)'))
                       ->where('g.is_deleted = 0')
                       ->group('g.gid');
        

        if(isset($options['parent_id']))
        {
            $select->where('g.parent_id = ?', $options['parent_id']);
        }
        
        if(isset($options['gid']))
        {
            if(is_array($options['gid']))
            {
                $select->where('g.gid IN (?)', $options['gid']);
            }
        }
        
        if(isset($options['depth']))
        {
            $select->where('g.depth = ?', $options['depth']);
        }
        
        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $groups[$result['group_id']] = array(
                    'gid'           =>    $result['group_id'],
                    'name'          =>    $result['name'],
                    'parent_id'     =>    $result['parent_id'],
                    'translation'   =>    array(
                        'title'   =>    $result['group_title']
                    ),
                    'count_groups'  =>    $result['count_groups'],
                    'count_users'   =>    $result['count_users']
                );
                
            }
        }

        return (isset($groups) ? $groups : false);
    }
        
    public function getGroup ($options, $language = LOCALE_ID)
    {        
        $groupPermissionsModels = new Models_GroupsPermissions;
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name))
                       ->joinLeft(array('gt' => 'groups_translation'), 'gt.gid = g.gid AND gt.lid = "'.$language.'"', array('group_title' => 'gt.title'))
                       ->where('g.is_deleted = 0')
                       ->group('g.gid');
        
        if(isset($options) && is_array($options))
        {
            if(isset($options['gid']))
            {
                $select->where('g.gid = ?', $options['gid']);
            }
        }
            
        $result = $this->fetchRow($select);
        
        if($result)
        {
            $groupsPermissions = $groupPermissionsModels->getGroupsPermissions(array('gid' => $result['gid']));
            
            if($groupsPermissions)
            {
                foreach($groupsPermissions as $permission)
                {
                    $permissions[$permission['pid']] = $permission['is_allowed'];
                }
            }

            return array(
                'gid'           =>    $result['gid'],
                'name'          =>    $result['name'],
                'path'          =>    $result['path'],
                'slug'          =>    $result['slug'],
                'depth'         =>    $result['depth'],
                'parent_id'     =>    $result['parent_id'],
                'permissions'   =>   (isset($permissions) ? $permissions : null),
                'translation'   =>    array(
                    'title'         =>    $result['group_title'],
                )
            );
        }
        
        return false;
    }
        
    public function addGroup ($formData)
    {        
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'name'       => str_replace('-', '_', $formData['name']),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null),
                'is_deleted' => 0
            );
            
            if($this->isGroupUnique($data['name'], $data['parent_id']) == false)
            {
                throw new Cms_Form_Exception('Nazwa grupy musi być unikalna.');
            }
            
            $last_id = $this->insert($data);

            $parentGroup = $this->getGroup(array('gid' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $groupAdditionalData = array(
                    'path'      => ($parentGroup['path'].'.'.$last_id),
                    'slug'      => ($parentGroup['slug'].'-'.str_replace('-', '_', $formData['name'])),
                    'depth'     => ($parentGroup['depth'] + 1)
                    );
            }
            else
            {
                $groupAdditionalData = array(
                    'path'      => $last_id,
                    'slug'      => str_replace('-', '_', $formData['name']),
                    'depth'     => 1
                    );
            }
            
            $this->update($groupAdditionalData, $this->getAdapter()->quoteInto('gid = ?', $last_id));
            
            $groupsPermissionsModels = new Models_GroupsPermissions;
            
            if(is_array($formData['permissions']))
            {
                foreach($formData['permissions'] as $pid => $value)
                {
                    if($value != '')
                    {
                        $groupsPermissionsModels->insert(array(
                            'gid'           =>    $last_id,
                            'pid'           =>    $pid,
                            'is_allowed'    =>    $value
                        ));
                    }
                }
            }            
            
            $groupsTranslationModels = new Models_GroupsTranslation;
            $languagesModels         = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Groups'));
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $groupTranslationData = $formData['languages'][$language['code']];
                    $groupTranslationData['gid'] = $last_id;
                    $groupTranslationData['lid']  = $language['lid'];
                    
                    $groupsTranslationModels->insert($groupTranslationData);
                                 
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $groupTranslationData['title']
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
    

    public function editGroup ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'name'       => str_replace('-', '_', $formData['name']),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null),
                'path'       => $formData['path'],
                'slug'       => $formData['slug'],
                'depth'      => $formData['depth'],
                'is_deleted' => 0
            );
            
            if($this->isGroupUnique($data['name'], $data['parent_id'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa grupy musi być unikalna.');
            }
            
            $oldPath = $data['path'];
            $oldSlug = $data['slug'];
            $parentGroup = $this->getGroup(array('gid' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $data['path'] = $parentGroup['path'].'.'.$id;
                $data['slug'] = $parentGroup['slug'].'-'.$data['name'];
                $data['depth'] = ($parentGroup['depth'] + 1);
            }
            else
            {
                $data['path'] = $id;
                $data['slug'] = str_replace('-', '_', $formData['name']);
                $data['depth'] = 1;
            }
            
            $where = $this->getAdapter()->quoteInto('gid = ?', $id);
            $this->update($data, $where);
            
            if($data['depth'] > ($data['parent_id'] == null ? 0 : $parentGroup['depth']))
            {
                    $setDepth = 'depth-'.($data['depth'] - ($data['parent_id'] == null ? 0 : $parentGroup['depth']) - 1);
            }
            elseif($data['depth'] == ($data['parent_id'] == null ? 0 : $parentGroup['depth']))
            {
                    $setDepth = 'depth+1';
            }
            else
            {
                    $setDepth = 'depth+'.(($data['parent_id'] == null ? 0 : $parentGroup['depth']) - $data['depth'] + 1);
            }

            $groupAdditionalData = array(
                'path' => new Zend_Db_Expr('REPLACE(path, "'.$oldPath.'","'.$data['path'].'")'),
                'slug' => new Zend_Db_Expr('REPLACE(slug, "'.$oldSlug.'","'.$data['slug'].'")'),
                'depth' => new Zend_Db_Expr($setDepth)
            );

            $where = array(
                $this->getAdapter()->quoteInto('path LIKE(?)', $oldPath.'%'),
                $this->getAdapter()->quoteInto('gid <> ?', $id)
            );
            
            $this->update($groupAdditionalData, $where);
            
            // Zapis uprawnień dla grup i użytkowników

            $groupsPermissionsModels = new Models_GroupsPermissions;

            $where = $this->getAdapter()->quoteInto('gid = ?', $id);                
            $groupsPermissionsModels->delete($where);

            if(is_array($formData['permissions']))
            {
                foreach($formData['permissions'] as $pid => $value)
                {         
                    if($value != '')
                    {
                        $groupsPermissionsModels->insert(array(
                            'gid'            =>    $id,
                            'pid'            =>    $pid,
                            'is_allowed'     =>    $value
                        ));
                    }
                }
            }
            
            // Zapisywanie translacji
            
            $groupsTranslationModels = new Models_GroupsTranslation;
            $languagesModels         = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Groups'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $groupTranslationData = $formData['languages'][$language['code']];
                    
                    $where = array(
                        $this->getAdapter()->quoteInto('gid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );
                    
                    if($groupsTranslationModels->fetchRow($where))
                    {
                        $groupsTranslationModels->update($groupTranslationData, $where);
                    }
                    else
                    {
                        $groupTranslationData['lid']  = $language['lid'];
                        $groupTranslationData['gid']  = $id;
                        $groupsTranslationModels->insert($groupTranslationData);
                    }    
                                   
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $groupTranslationData['title']
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
    
    public function deleteGroups ($groups)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearchGroups  = new Cms_Search();
            $cmsSearchGroups->setIndex(Cms_Search_Index::getIndex('Groups'));
            
            if(is_array($groups))
            {
                $where = $this->getAdapter()->quoteInto('gid IN(?)', $groups);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('gid = ?', $groups);
            }

            $this->delete($where);
            
            $cmsSearchGroups->deleteDocument($groups);
            
            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    
    public function getGroupsTree ($options = array(), $language = LOCALE_ID)
    {        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name), array('*', 'group_id' => 'g.gid'))
                       ->joinLeft(array('gt' => 'groups_translation'), 'gt.gid = g.gid AND gt.lid = "'.$language.'"', array('group_title' => 'gt.title'))
                       ->where('g.is_deleted = 0')
                       ->group('g.gid');
        
        if(isset($options['exclude_childrens']))
        {
            if(isset($options['exclude_childrens']['gid']))
            {
                $group = $this->getGroup(array('gid' => $options['exclude_childrens']['gid']));
                
                $path = $group['path'];
            }
            elseif(isset($options['exclude_childrens']['path']))
            {
                $path = $options['exclude_childrens']['path'];
            }
            
            $select->where('g.path NOT LIKE ?', $path);
        }
        
        $results = $this->fetchAll($select);
        
        if($results)
        {
            if(!isset($options['exclude_root']) || $options['exclude_root'] == false)
            {
                $groups[0][0] = array(
                    'pid'         =>   0,
                    'title'       =>   'Kategoria główna',
                    'depth'       =>   0,
                    'parent_id'   =>   null,
                    'value'       =>   0
                );
            }
            
            foreach($results as $result)
            {
                $groups[($result['parent_id'] == NULL ? 0 : $result['parent_id'])][$result['group_id']] = array(
                    'gid'           =>    $result['group_id'],
                    'name'          =>    $result['name'],
                    'depth'         =>    $result['depth'],
                    'slug'          =>    $result['slug'],
                    'parent_id'     =>    $result['parent_id'],
                    'group_title'   =>    $result['group_title'],                    
                    'id'            =>    $result['group_id'],
                    'title'         =>    $result['group_title'],
                    'value'         =>    $result['group_id']
                );
                
            }
        }

        return (isset($groups) ? $groups : false);
    }    
    
    public function getGroupsForACL ($language = LOCALE_ID)
    {        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name), array('*', 'group_id' => 'g.gid'))
                       ->joinLeft(array('gt' => 'groups_translation'), 'gt.gid = g.gid AND gt.lid = "'.$language.'"', array('group_title' => 'gt.title'))
                       ->where('g.is_deleted = 0')
                       ->order('g.depth ASC');
  
        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $groups[$result['group_id']] = array(
                    'gid'           =>    $result['group_id'],
                    'name'          =>    $result['name'],
                    'depth'         =>    $result['depth'],
                    'slug'          =>    $result['slug'],
                    'parent_id'     =>    $result['parent_id'],
                    'group_title'   =>    $result['group_title'],
                );
                
            }
        }

        return (isset($groups) ? $groups : false);
    }

    /*
     * Sprawdzanie czy grupa jest unikalna
     */
    
    public function isGroupUnique ($name, $parent_id, $id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name))
                       ->where('g.name = ?', $name);

        if($id != null)
        {
            $select->where('g.gid <> ?', $id);
        }
        
        if($parent_id == null)
        {
            $select->where('g.parent_id IS NULL');
        }
        else 
        {
            $select->where('g.parent_id = ?', $parent_id);
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
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('g' => $this->_name))
                       ->where('g.is_deleted = 0')
                       ->where('g.gid = ?', $id);

        $group = $this->fetchRow($select);
        
        if($group)
        {
            $groups = explode('.', $group['path']);

            $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('g' => $this->_name))
                        ->joinLeft(array('gt' => 'groups_translation'), 'gt.gid = g.gid AND gt.lid = "'.$language.'"', array('group_title' => 'gt.title'))
                        ->where('g.is_deleted = 0')
                        ->where('g.gid IN (?)', $groups)
                        ->order('g.depth DESC')
                        ->group('g.gid');

            $results =$this->fetchAll($select);
            
            if($results)
            {
                foreach($results as $result)
                {
                    $breadcrumbs[] = array(
                        'gid'         => $result['gid'],
                        'group_title' => $result['group_title']
                    );
                }
            }
        }
        
        return (isset($breadcrumbs) ? $breadcrumbs : false);
    }
}
?>
