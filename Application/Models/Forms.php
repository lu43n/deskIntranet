<?php
class Models_Forms extends Cms_Db
{
    public $_name = 'forms';
    protected $_primary = 'fid';
    protected $_dependentTables = array(
        "Models_FormsFields",
        "Models_FormsTranslation",
        "Models_Forms"
    );
    protected $_referenceMap = array(
        "Forms" => array(
            "columns" => array("fid"),
            "refTableClass" => "Models_Forms",
            "refColumns" => array("parent_id")
        )
    );        

    /*
     * Pobieranie formularzy
     */
        
    public function getForms ($options, $language = LOCALE_ID)
    {        
        $acl = Zend_Registry::get('acl');

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('f' => $this->_name), array('*', 'form_id' => 'f.fid'))
                       ->joinLeft(array('ft' => 'forms_translation'), 'ft.fid = f.fid AND ft.lid = "'.$language.'"', array('form_title' => 'ft.title', 'form_description' => 'ft.description'))
                       ->joinLeft(array('f2' => 'forms'), 'f2.parent_id = f.fid AND f2.is_deleted = 0', array('count_forms' => 'COUNT(DISTINCT f2.fid)'))
                       ->joinLeft(array('ff' => 'forms_fields'), 'ff.fid = f.fid AND ff.is_deleted = 0', array('count_fields' => 'COUNT(DISTINCT ff.ffid)'))
                       ->where('f.is_deleted = 0')
                       ->group('f.fid');
        
        if(isset($options) && is_array($options))
        {
            if(isset($options['fid']))
            {
                if(is_array($options['fid']))
                {
                    $select->where('f.fid IN (?)', $options['fid']);
                }
            }
            
            if(isset($options['parent_id']))
            {
                $select->where('f.parent_id = ?', $options['parent_id']);
            }

            if(isset($options['depth']))
            {
                $select->where('f.depth = ?', $options['depth']);
            }
        }
        else
        {
            return array();
        }
        
        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $forms[$result['form_id']] = array(
                    'fid'           =>    $result['form_id'],
                    'parent_id'     =>    $result['parent_id'],
                    'name'          =>    $result['name'],
                    'slug'          =>    $result['slug'],
                    'path'          =>    $result['path'],
                    'depth'         =>    $result['depth'],
                    'is_deleted'    =>    $result['is_deleted'],
                    'translation'  =>    array(
                        'title'        =>  $result['form_title'],
                        'description'  =>  $result['form_description']
                    ),
                    'count_fields'  =>    $result['count_fields'],
                    'count_forms'   =>    $result['count_forms'],
                );
                
            }
        }

        return (isset($forms) ? $forms : array());
    }
    
    /*
     * Pobieranie jednego formularza
     */
        
    public function getForm ($options, $language = LOCALE_ID)
    {            
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('f' => $this->_name))
                       ->joinLeft(array('ft' => 'forms_translation'), 'ft.fid = f.fid AND ft.lid = "'.$language.'"', array('form_title' => 'ft.title', 'form_description' => 'ft.description'))
                       ->joinLeft(array('ff' => 'forms_fields'), 'ff.fid = f.fid', array('field_id' => 'ff.ffid', 'field_name' => 'ff.name', 'is_field_deleted' => 'ff.is_deleted', 'field_filters' => 'ff.filters', 'field_validators' => 'ff.validators', 'field_type' => 'ff.type', 'field_sort' => 'ff.sort'))
                       ->where('f.is_deleted = 0');
        
        if(isset($options) && is_array($options))
        {
            if(isset($options['fid']))
            {
                $select->where('f.fid = ?', $options['fid']);
            }
            
            if(isset($options['slug']))
            {
                $select->where('f.slug = ?', $options['slug']);
            }
        }
        else
        {
            return array();
        }
        
        $result = $this->fetchRow($select);
        
        if($result)
        {
            $form = array(
                'fid'            =>    $result['fid'],
                'name'           =>    $result['name'],
                'path'           =>    $result['path'],
                'slug'           =>    $result['slug'],
                'depth'          =>    $result['depth'],
                'parent_id'      =>    $result['parent_id'],
                'is_deleted'     =>    $result['is_deleted'],
                'translation'    =>    array(
                    'title'        =>    $result['form_title'],
                    'description'  =>    $result['form_description']
                )
            );
        }

        return (isset($form) ? $form : array());
    }     
    
    /*
     * Dodawanie formularza
     */

    public function addForm ($formData)
    {   
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'name'       => str_replace('-', '_', $formData['name']),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null),
                'is_deleted' => 0
            );
            
            if($this->isFormUnique($data['name'], $data['parent_id']) == false)
            {
                throw new Cms_Form_Exception('Nazwa formularza musi być unikalna.');
            }
            
            $last_id = $this->insert($data);

            $parentCategory = $this->getForm(array('fid' => $data['parent_id']));
            
            if($data['parent_id'] != null)
            {
                $additionalData = array(
                    'path'      => ($parentCategory['path'].'.'.$last_id),
                    'slug'      => ($parentCategory['slug'].'-'.$data['name']),
                    'depth'     => ($parentCategory['depth'] + 1)
                    );
            }
            else
            {
                $additionalData = array(
                    'path'      => $last_id,
                    'slug'      => $data['name'],
                    'depth'     => 1
                    );
            }
            
            $this->update($additionalData, $this->getAdapter()->quoteInto('fid = ?', $last_id));
            
            $formsTranslationModels = new Models_FormsTranslation;
            $languagesModels        = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Forms'));
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $formTranslationData = $formData['languages'][$language['code']];
                    $formTranslationData['fid'] = $last_id;
                    $formTranslationData['lid']  = $language['lid'];
                    
                    $formsTranslationModels->insert($formTranslationData);
                       
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'slug'           =>   $additionalData['slug'],   
                        'title'          =>   $formTranslationData['title'],
                        'description'    =>   $formTranslationData['description']
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
    
    /*
     * Edycja formularza
     */
    
    public function editForm ($formData, $id)
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
            
            if($this->isFormUnique($data['name'], $data['parent_id'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa formularza musi być unikalna.');
            }
            
            $oldPath = $data['path'];
            $oldSlug = $data['slug'];
            $parentCategory = $this->getForm(array('fid' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $data['path']  = $parentCategory['path'].'.'.$id;
                $data['slug']  = $parentCategory['slug'].'-'.$data['name'];
                $data['depth'] = ($parentCategory['depth'] + 1);
            }
            else
            {
                $data['path'] = $id;
                $data['slug'] = $data['name'];
                $data['depth'] = 1;
            }

            $where = $this->getAdapter()->quoteInto('fid = ?', $id);
            $this->update($data, $where);
            
            if($data['depth'] > ($data['parent_id'] == null ? 0 : $parentCategory['depth']))
            {
                    $setDepth = 'depth-'.($data['depth'] - ($data['parent_id'] == null ? 0 : $parentCategory['depth']) - 1);
            }
            elseif($data['depth'] == ($data['parent_id'] == null ? 0 : $parentCategory['depth']))
            {
                    $setDepth = 'depth+1';
            }
            else
            {
                    $setDepth = 'depth+'.(($data['parent_id'] == null ? 0 : $parentCategory['depth']) - $data['depth'] + 1);
            }

            $additionalData = array(
                'path' => new Zend_Db_Expr('REPLACE(path, "'.$oldPath.'","'.$data['path'].'")'),
                'slug' => new Zend_Db_Expr('REPLACE(slug, "'.$oldSlug.'","'.$data['slug'].'")'),
                'depth' => new Zend_Db_Expr($setDepth)
            );

            $where = array(
                $this->getAdapter()->quoteInto('path LIKE(?)', $oldPath.'%'),
                $this->getAdapter()->quoteInto('fid <> ?', $id)
            );
            
            $this->update($additionalData, $where);
            
            // Zapisywanie translacji
            
            $formsTranslationModels = new Models_FormsTranslation;
            $languagesModels        = new Models_Languages;  
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Forms'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $formTranslationData = $formData['languages'][$language['code']];
                    
                    $where = array(
                        $this->getAdapter()->quoteInto('fid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );
                    
                    if($formsTranslationModels->fetchRow($where))
                    {
                        $formsTranslationModels->update($formTranslationData, $where);
                    }
                    else
                    {
                        $formTranslationData['lid'] = $language['lid'];
                        $formTranslationData['fid'] = $id;
                        $formsTranslationModels->insert($formTranslationData);
                    }     
                    
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $formTranslationData['title'],
                        'description'    =>   $formTranslationData['description']
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

    /*
     * Usuwanie formularzy
     */
    
    public function deleteForms ($forms)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearchForm  = new Cms_Search();
            $cmsSearchForm->setIndex(Cms_Search_Index::getIndex('Forms'));
          
            $cmsSearchFormFields  = new Cms_Search();
            $cmsSearchFormFields->setIndex(Cms_Search_Index::getIndex('Forms-Fields'));
            
            if(is_array($forms))
            {
                $where = $this->getAdapter()->quoteInto('fid IN(?)', $forms);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('fid = ?', $forms);
            }

            $this->delete($where);

            $cmsSearchForm->deleteDocument($forms);
            $cmsSearchFormFields->deleteDocument($forms, 'fid');
            
            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }     
    
    /*
     * Sprawdzanie czy formularz jest unikalny
     */
    
    public function isFormUnique ($name, $parent_id, $id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('f' => $this->_name))
                       ->where('f.name = ?', $name);

        if($id != null)
        {
            $select->where('f.fid <> ?', $id);
        }
        
        if($parent_id == null)
        {
            $select->where('f.parent_id IS NULL');
        }
        else 
        {
            $select->where('f.parent_id = ?', $parent_id);
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
    
    /*
     * Breadcrumbs
     */
        
    public function getBreadcrumbs ($id, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('f' => $this->_name))
                       ->where('f.is_deleted = 0')
                       ->where('f.fid = ?', $id);

        $form = $this->fetchRow($select);
        
        if($form)
        {
            $forms = explode('.', $form['path']);

            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('f' => $this->_name), array('*', 'form_id' => 'fid'))
                           ->joinLeft(array('ft' => 'forms_translation'), 'ft.fid = f.fid AND ft.lid = "'.$language.'"', array('form_title' => 'ft.title'))
                           ->where('f.is_deleted = 0')
                           ->where('f.fid IN (?)', $forms)
                           ->order('f.depth DESC')
                           ->group('f.fid');
            
            $results = $this->fetchAll($select);
            
            if($results)
            {
                foreach($results as $result)
                {
                    $breadcrumbs[] = array(
                        'fid'   => $result['fid'],
                        'title' => $result['form_title']
                    );
                }
            }
            
        }
        
        return (isset($breadcrumbs) ? $breadcrumbs : array());
    }
    
    /**
     * Pobiera drzewo formularzy
     */    
        
    public function getTreeForms ($options = array(), $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('f' => $this->_name), array('*', 'form_id' => 'f.fid'))
                       ->joinLeft(array('ft' => 'forms_translation'), 'ft.fid = f.fid AND ft.lid = "'.$language.'"', array('form_title' => 'ft.title', 'form_description' => 'ft.description'))
                       ->joinLeft(array('f2' => 'forms'), 'f2.parent_id = f.fid AND f2.is_deleted = 0', array('count_forms' => 'COUNT(f2.fid)'))
                       ->joinLeft(array('ff' => 'forms_fields'), 'ff.fid = f.fid AND ff.is_deleted = 0', array('count_fields' => 'COUNT(ff.ffid)'))
                       ->where('f.is_deleted = 0')
                       ->order('f.path')
                       ->group('f.fid');
                          
        if(isset($options['exclude_childrens']))
        {
            if(isset($options['exclude_childrens']['fid']))
            {
                $form = $this->getForm(array('fid' => $options['exclude_childrens']['fid']));
                
                $path = $form['path'];
            }
            elseif(isset($options['exclude_childrens']['path']))
            {
                $path = $options['exclude_childrens']['path'];
            }
            
            $select->where('f.path NOT LIKE ?', $path);
        }
        
        $results = $this->fetchAll($select);
        
        $forms = array();
        if($results)
        {
            if(!isset($options['exclude_root']) || $options['exclude_root'] == false)
            {
                $forms[0][0] = array(
                    'pid'         =>   0,
                    'title'       =>   'Kategoria główna',
                    'depth'       =>   0,
                    'parent_id'   =>   null,
                    'value'       =>   0
                );
            }
            
            foreach($results as $result)
            {
                $forms[($result['parent_id'] == NULL ? 0 : $result['parent_id'])][$result['form_id']] = array(
                    'id'           =>    $result['form_id'],
                    'depth'        =>    $result['depth'],
                    'title'        =>    $result['form_title'],
                    'value'        =>    $result['form_id'],
                );
            }
        }

        return $forms;
    }

    
        
}
?>
