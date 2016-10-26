<?php
class Models_Documents extends Cms_Db 
{
    protected $_name = 'documents';
    protected $_primary = 'did';
    protected $_dependentTables = array(
        "Models_Documents"
    );
    protected $_referenceMap = array(
        "Documents" => array(
            "columns" => array("parent_id"),
            "refTableClass" => "Models_Documents",
            "refColumns" => array("did")
        )
    );

    public function getDocuments ($options = false, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name))
                       ->joinLeft(array('d2' => 'documents'), 'd2.parent_id = d.did', array('count_documents' => 'COUNT(DISTINCT d2.did)'))
                       ->joinLeft(array('dt' => 'documents_translation'), 'dt.did = d.did AND dt.lid = "'.$language.'"', array('document_title' => 'dt.title', 'document_attachments' => 'dt.attachments', 'document_content' => 'dt.content'))
                       ->group('d.did');

        if(isset($options) && is_array($options))
        {
            if(isset($options['did']))
            {
                if(is_array($options['did']))
                {
                    $select->where('d.did IN (?)', $options['did']);
                }
            }
            
            if(isset($options['name']))
            {
                if(is_array($options['name']))
                {
                    $select->where('d.name IN (?)', $options['name']);
                }
                else
                {
                    $select->where('d.name = ?', $options['name']);
                }
            }
            
            if(isset($options['order']))
            {
                $select->order('d.'.$options['order']);
            }
            
            if(isset($options['type']))
            {
                $select->where('d.type = ?', $options['type']);
            }

            if(isset($options['parent_id']))
            {
                $select->where('d.parent_id = ?', $options['parent_id']);
            }

            if(isset($options['depth']))
            {
                $select->where('d.depth = ?', $options['depth']);
            }
            
            if(isset($options['limit']))
            {
                $select->limit($options['limit']);
            }
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            $usersModels = new Models_Users;
            
            foreach($results as $result)
            {
                $user = $usersModels->getUser(array('uid' => $result['uid']));
                
                $documents[$result['did']] = array(
                    'did'              =>   $result['did'],
                    'uid'              =>   $result['uid'],
                    'name'             =>   $result['name'],
                    'type'             =>   $result['type'],
                    'hash'             =>   $result['hash'],
                    'depth'            =>   $result['depth'],
                    'translation'      =>   array(
                            'title'       =>   $result['document_title'],
                            'attachments' =>   $result['document_attachments'],
                            'content'     =>   $result['document_content'],
                    ),
                    'slug'             =>   $result['slug'],
                    'path'             =>   $result['path'],
                    'parent_id'        =>   $result['parent_id'],
                    'created_at'       =>   $result['created_at'],
                    'modified_at'      =>   $result['modified_at'],
                    'count_documents'  =>   $result['count_documents'],
                    'user'             =>   $user
                );
            }
        }

        return (isset($documents) ? $documents : false);
    }

    public function getDocument ($options, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name))
                       ->joinLeft(array('dt' => 'documents_translation'), 'dt.did = d.did AND dt.lid = "'.$language.'"', array('document_title' => 'dt.title', 'document_attachments' => 'dt.attachments', 'document_content' => 'dt.content'));

        if(isset($options) && is_array($options))
        {
            if(isset($options['did']))
            {
                $select->where('d.did = ?', $options['did']);
            }
        }

        $result = $this->fetchRow($select);

        if($result)
        {
            $result = $result->toArray();

            $usersModels = new Models_Users;
            
            $user = $usersModels->getUser(array('uid' => $result['uid']));
            
            $documents = array(
                'did'         =>   $result['did'],
                'uid'         =>   $result['uid'],
                'name'        =>   $result['name'],
                'type'        =>   $result['type'],
                'hash'        =>   $result['hash'],
                'depth'       =>   $result['depth'],
                'translation' =>   array(
                    'title'       =>   $result['document_title'],
                    'attachments' =>   ($result['document_attachments'] != null ? json_decode($result['document_attachments'], true) : null),
                    'content'     =>   $result['document_content'],
                ),
                'slug'        =>   $result['slug'],
                'path'        =>   $result['path'],
                'parent_id'   =>   $result['parent_id'],
                'created_at'  =>   $result['created_at'],
                'modified_at' =>   $result['modified_at'],
                'user'        =>   $user
            );
        }

        return (isset($documents) ? $documents : false);
    }

    public function getDocumentsTree ($options = array(), $language = LOCALE_ID)
    {        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name))
                       ->joinLeft(array('dt' => 'documents_translation'), 'dt.did = d.did AND dt.lid = "'.$language.'"', array('document_title' => 'dt.title'));

        if(isset($options['only_dirs']))
        {
            $select->where('d.type = "dir"');
        }
        
        if(isset($options['only_docs']))
        {
            $select->where('d.type = "doc"');
        }
        
        $results = $this->fetchAll($select);
        
        if($results)
        {
            if(!isset($options['exclude_root']) || $options['exclude_root'] == false)
            {
                $documents[0][0] = array(
                    'did'         =>   0,
                    'title'       =>   'Kategoria główna',
                    'depth'       =>   0,
                    'parent_id'   =>   null,
                    'value'       =>   0
                );
            }
            
            foreach($results as $result)
            {
                
                $documents[($result['parent_id'] == NULL ? 0 : $result['parent_id'])][$result['did']] = array(
                    'did'         =>   $result['did'],
                    'name'        =>   $result['name'],
                    'depth'       =>   $result['depth'],
                    'slug'        =>   $result['slug'],
                    'path'        =>   $result['path'],
                    'parent_id'   =>   $result['parent_id'],
                    'id'          =>   $result['did'],
                    'title'       =>   $result['name'],
                    'value'       =>   $result['did'],
                );
            }
        }

        return $documents;
    }

    public function addDirectory ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'type'       => 'DIR',
                'uid'        => $formData['uid'],
                'name'       => str_replace('-', '_', $formData['name']),
                'hash'       => md5(time().''.$formData['name']),
                'created_at' => new Zend_Db_Expr('NOW()'),
                'modified_at' => new Zend_Db_Expr('NOW()'),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null)
            );
            
            if($this->isDocumentUnique($data['name'], $data['parent_id']) == false)
            {
                throw new Cms_Form_Exception('Nazwa uprawnienia musi być unikalna.');
            }

            $last_id = $this->insert($data);

            $parentDocument = $this->getDocument(array('did' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $documentsAddtionalData = array(
                    'path'      => ($parentDocument['path'].'.'.$last_id),
                    'slug'      => ($parentDocument['slug'].'-'.str_replace('-', '_', $formData['name'])),
                    'depth'     => ($parentDocument['depth'] + 1)
                    );
            }
            else
            {
                $documentsAddtionalData = array(
                    'path'      => $last_id,
                    'slug'      => str_replace('-', '_', $formData['name']),
                    'depth'     => 1
                    );
            }

            $this->update($documentsAddtionalData, $this->getAdapter()->quoteInto('did = ?', $last_id));

            $documentsTranslationModels = new Models_DocumentsTranslation;
            $languagesModels            = new Models_Languages;

            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $documentsTranslationData = $formData['languages'][$language['code']];
                    $documentsTranslationData['did']  = $last_id;
                    $documentsTranslationData['lid']  = $language['lid'];

                    $documentsTranslationModels->insert($documentsTranslationData);
                }
            }            

            $this->getAdapter()->commit();

        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }

    public function addDocument ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'type'        => 'DOC',
                'uid'         => $formData['uid'],
                'name'        => str_replace('-', '_', $formData['name']),
                'hash'        => md5(time().''.$formData['name']),
                'created_at'  => new Zend_Db_Expr('NOW()'),
                'modified_at' => new Zend_Db_Expr('NOW()'),
                'parent_id'   => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null)
            );
            
            if($this->isDocumentUnique($data['name'], $data['parent_id']) == false)
            {
                throw new Cms_Form_Exception('Nazwa uprawnienia musi być unikalna.');
            }

            $last_id = $this->insert($data);

            $parentDocument = $this->getDocument(array('did' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $documentsAddtionalData = array(
                    'path'      => ($parentDocument['path'].'.'.$last_id),
                    'slug'      => ($parentDocument['slug'].'-'.str_replace('-', '_', $formData['name'])),
                    'depth'     => ($parentDocument['depth'] + 1)
                    );
            }
            else
            {
                $documentsAddtionalData = array(
                    'path'      => $last_id,
                    'slug'      => str_replace('-', '_', $formData['name']),
                    'depth'     => 1
                    );
            }

            $this->update($documentsAddtionalData, $this->getAdapter()->quoteInto('did = ?', $last_id));

            $documentsTranslationModels = new Models_DocumentsTranslation;
            $languagesModels            = new Models_Languages;

            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Documents'));
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $documentsTranslationData = $formData['languages'][$language['code']];
                    $documentsTranslationData['did']  = $last_id;
                    $documentsTranslationData['lid']  = $language['lid'];

                    $documentsTranslationModels->insert($documentsTranslationData);
     
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $documentsTranslationData['title'],
                        'content'        =>   $documentsTranslationData['content']
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

    public function editDirectory ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'type'       => 'DIR',
                'uid'        => $formData['uid'],
                'name'       => str_replace('-', '_', $formData['name']),
                'hash'       => md5(time().''.$formData['name']),
                'modified_at' => new Zend_Db_Expr('NOW()'),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null)
            );
            
            if($this->isDocumentUnique($data['name'], $data['parent_id'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa dokumentu musi być unikalna.');
            }

            $oldPath = $data['path'];
            $oldSlug = $data['slug'];
            $parentDocument = $this->getDocument(array('did' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $data['path'] = $parentDocument['path'].'.'.$id;
                $data['slug'] = $parentDocument['slug'].'-'.$data['name'];
                $data['depth'] = ($parentDocument['depth'] + 1);
            }
            else
            {
                $data['path'] = $id;
                $data['slug'] = str_replace('-', '_', $formData['name']);
                $data['depth'] = 1;
            }

            $where = $this->getAdapter()->quoteInto('did = ?', $id);
            $this->update($data, $where);

            if($data['depth'] > ($data['parent_id'] == null ? 0 : $parentDocument['depth']))
            {
                    $setDepth = 'depth-'.($data['depth'] - ($data['parent_id'] == null ? 0 : $parentDocument['depth']) - 1);
            }
            elseif($data['depth'] == ($data['parent_id'] == null ? 0 : $parentDocument['depth']))
            {
                    $setDepth = 'depth+1';
            }
            else
            {
                    $setDepth = 'depth+'.(($data['parent_id'] == null ? 0 : $parentDocument['depth']) - $data['depth'] + 1);
            }

            $documentsAdditionalData = array(
                'path' => new Zend_Db_Expr('REPLACE(path, "'.$oldPath.'","'.$data['path'].'")'),
                'slug' => new Zend_Db_Expr('REPLACE(slug, "'.$oldSlug.'","'.$data['slug'].'")'),
                'depth' => new Zend_Db_Expr($setDepth)
            );

            $where = array(
                $this->getAdapter()->quoteInto('path LIKE(?)', $oldPath.'%'),
                $this->getAdapter()->quoteInto('did <> ?', $id)
            );

            $this->update($documentsAdditionalData, $where);

            // Zapisywanie translacji

            $documentsTranslationModels = new Models_DocumentsTranslation;
            $languagesModels            = new Models_Languages;

            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $documentsTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('did = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($documentsTranslationModels->fetchRow($where))
                    {
                        $documentsTranslationModels->update($documentsTranslationData, $where);
                    }
                    else
                    {
                        $documentsTranslationData['lid']  = $language['lid'];
                        $documentsTranslationData['did']  = $id;
                        $documentsTranslationModels->insert($documentsTranslationData);
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
    
    public function editDocument ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'type'       => 'DOC',
                'uid'        => $formData['uid'],
                'name'       => str_replace('-', '_', $formData['name']),
                'hash'       => md5(time().''.$formData['name']),
                'modified_at' => new Zend_Db_Expr('NOW()'),
                'parent_id'  => ($formData['parent_id'] != 0 ? $formData['parent_id'] : null)
            );
            
            if($this->isDocumentUnique($data['name'], $data['parent_id'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa dokumentu musi być unikalna.');
            }

            $oldPath = $data['path'];
            $oldSlug = $data['slug'];
            $parentDocument = $this->getDocument(array('did' => $data['parent_id']));

            if($data['parent_id'] != null)
            {
                $data['path'] = $parentDocument['path'].'.'.$id;
                $data['slug'] = $parentDocument['slug'].'-'.$data['name'];
                $data['depth'] = ($parentDocument['depth'] + 1);
            }
            else
            {
                $data['path'] = $id;
                $data['slug'] = str_replace('-', '_', $formData['name']);
                $data['depth'] = 1;
            }

            $where = $this->getAdapter()->quoteInto('did = ?', $id);
            $this->update($data, $where);

            if($data['depth'] > ($data['parent_id'] == null ? 0 : $parentDocument['depth']))
            {
                    $setDepth = 'depth-'.($data['depth'] - ($data['parent_id'] == null ? 0 : $parentDocument['depth']) - 1);
            }
            elseif($data['depth'] == ($data['parent_id'] == null ? 0 : $parentDocument['depth']))
            {
                    $setDepth = 'depth+1';
            }
            else
            {
                    $setDepth = 'depth+'.(($data['parent_id'] == null ? 0 : $parentDocument['depth']) - $data['depth'] + 1);
            }

            $documentsAdditionalData = array(
                'path' => new Zend_Db_Expr('REPLACE(path, "'.$oldPath.'","'.$data['path'].'")'),
                'slug' => new Zend_Db_Expr('REPLACE(slug, "'.$oldSlug.'","'.$data['slug'].'")'),
                'depth' => new Zend_Db_Expr($setDepth)
            );

            $where = array(
                $this->getAdapter()->quoteInto('path LIKE(?)', $oldPath.'%'),
                $this->getAdapter()->quoteInto('did <> ?', $id)
            );

            $this->update($documentsAdditionalData, $where);

            // Zapisywanie translacji

            $documentsTranslationModels = new Models_DocumentsTranslation;
            $languagesModels            = new Models_Languages;

            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Documents'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $documentsTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('did = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($documentsTranslationModels->fetchRow($where))
                    {
                        $documentsTranslationModels->update($documentsTranslationData, $where);
                    }
                    else
                    {
                        $documentsTranslationData['lid']  = $language['lid'];
                        $documentsTranslationData['did']  = $id;
                        $documentsTranslationModels->insert($documentsTranslationData);
                    }                   
                    
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'title'          =>   $documentsTranslationData['title'],
                        'content'        =>   $documentsTranslationData['content']
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

    public function deleteDocuments ($documents)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearch  = new Cms_Search();
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Documents'));
            
            if(is_array($documents))
            {
                $where = $this->getAdapter()->quoteInto('did IN(?)', $documents);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('did = ?', $documents);
            }
            
            $this->delete($where);
            
            $cmsSearch->deleteDocument($documents);

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
    
    public function isDocumentUnique ($name, $parent_id, $id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name))
                       ->where('d.name = ?', $name);

        if($id != null)
        {
            $select->where('d.did <> ?', $id);
        }
        
        if($parent_id == null)
        {
            $select->where('d.parent_id IS NULL');
        }
        else 
        {
            $select->where('d.parent_id = ?', $parent_id);
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
            $document = $this->getDocument(array('did' => $id));

            $path = explode('.', $document['path']);

            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('d' => $this->_name))
                           ->joinLeft(array('dt' => 'documents_translation'), 'dt.did = d.did AND dt.lid = "'.$language.'"', array('document_title' => 'dt.title'))
                           ->where('d.did IN (?)', $path)
                           ->order('d.depth DESC');

            $results = $this->fetchAll($select);

            if($results)
            {   
                $documents = array();

                foreach($results as $result)
                {
                    $documents[] = array(
                        'did'                =>   $result['did'],
                        'name'               =>   $result['name'],
                        'depth'              =>   $result['depth'],
                        'title'              =>   $result['name'],
                        'slug'               =>   $result['slug'],
                        'path'               =>   $result['path'],
                        'parent_id'          =>   $result['parent_id']
                    );  
                }
            }        
        }
        
        
        return (isset($documents) ? $documents : false);
    }
}
?>
