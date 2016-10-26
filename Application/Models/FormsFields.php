<?php
class Models_FormsFields extends Cms_Db
{
    protected $_name = 'forms_fields';
    protected $_primary = 'ffid';
    protected $_referenceMap = array(
        "FormsGroups" => array(
            "columns" => array("fid"),
            "refTableClass" => "Models_Forms",
            "refColumns" => array("fid")
        )
    );
    protected $_dependentTables = array(
        "Models_FormsFieldsTranslation"
    );   
    
    
    /**
     * Pobieranie pól formularza
     */   
        
    public function getFields ($options, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('ff' => $this->_name), array('*','field_id' => 'ff.ffid', 'field_name' => 'ff.name', 'form_id' => 'ff.fid'))
                       ->joinLeft(array('fft' => 'forms_fields_translation'), 'fft.ffid = ff.ffid AND fft.lid = '.$language.'', array('field_label' => 'fft.label', 'field_description' => 'fft.description', 'field_default' => 'fft.default', 'field_options' => 'fft.options'))
                       ->joinLeft(array('f' => 'forms'), 'f.fid = ff.fid')
                       ->where('ff.is_deleted = 0')
                       ->order('ff.sort ASC');
        
        if(isset($options) && is_array($options))
        {
            if(isset($options['fid']))
            {
                if(is_array($options['fid']))
                {
                    $select->where('ff.fid IN (?)', $options['fid']);
                }
                else
                {
                    $select->where('ff.fid = ?', $options['fid']);
                }
            }
            
            if(isset($options['ffid']))
            {
                if(is_array($options['ffid']))
                {
                    $select->where('ff.ffid IN (?)', $options['ffid']);
                }
            }
            
            if(isset($options['slug']))
            {
                $select->where('f.slug = ?', $options['slug']);
            }
        }
        
        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $fields[$result['field_id']] = array(
                    'ffid'            =>    $result['field_id'],
                    'fid'             =>    $result['form_id'],
                    'name'            =>    $result['field_name'],
                    'filters'         =>    $result['filters'],
                    'validators'      =>    $result['validators'],
                    'type'            =>    $result['type'],
                    'is_multilingual' =>    $result['is_multilingual'],
                    'is_deleted'      =>    $result['is_deleted'],
                    'translation'    =>    array(
                        'label'        =>   $result['field_label'],
                        'options'      =>   $result['field_options'],
                        'description'  =>   $result['field_description'],
                        'default'      =>   $result['field_default']
                    )
                );
            }
        }

        return (isset($fields) ? $fields : false);
    }
    
    /**
     * Pobieranie jednego pola formularza
     */
    
    public function getField ($options, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('ff' => $this->_name), array('*', 'field_id' => 'ff.ffid', 'form_id' => 'ff.fid'))
                       ->joinLeft(array('fft' => 'forms_fields_translation'), 'fft.ffid = ff.ffid AND fft.lid = '.$language.'', array('field_label' => 'fft.label', 'field_description' => 'fft.description', 'field_default' => 'fft.default', 'field_options' => 'fft.options'))
                       ->where('ff.is_deleted = 0');

        if(isset($options) && is_array($options))
        {
            if(isset($options['ffid']))
            {
                $select->where('ff.ffid = ?', $options['ffid']);
            }
        }
        
        $result = $this->fetchRow($select);
        
        if($result)
        {
            $field = array(
                'ffid'            =>    $result['field_id'],
                'fid'             =>    $result['form_id'],
                'name'            =>    $result['name'],
                'filters'         =>    $result['filters'],
                'validators'      =>    $result['validators'],
                'type'            =>    $result['type'],
                'is_multilingual' =>    $result['is_multilingual'],
                'is_deleted'      =>    $result['is_deleted'],
                'translation'    =>    array(
                    'label'        =>   $result['field_label'],
                    'options'      =>   $result['field_options'],
                    'description'  =>   $result['field_description'],
                    'default'      =>   $result['field_default']
                )
            );
        }
        
        return (isset($field) ? $field : false);
    }
    
    /*
     * Dodawanie pól formularza
     */
    
    public function addField ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'fid'             =>    $formData['fid'],
                'name'            =>    str_replace('-', '_', $formData['name']),
                'filters'         =>    (isset($formData['filters']) ? implode(',', $formData['filters']) : null ),
                'validators'      =>    (isset($formData['validators']) ? implode(',', $formData['validators']) : null),
                'type'            =>    $formData['type'],
                'is_multilingual' =>    $formData['is_multilingual'],
                'is_deleted'      =>    0
            );
            
            if($this->isFieldUnique($data['name'], $data['fid']) == false)
            {
                throw new Cms_Form_Exception('Nazwa pola formularza musi być unikalna.');
            }

            $last_id = $this->insert($data);

            $formsFieldsTranslationModels = new Models_FormsFieldsTranslation;
            $languagesModels              = new Models_Languages;

            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Forms-Fields'));
            
            $languages = $languagesModels->getLanguages();   
            if($languages)
            {
                foreach($languages as $language)
                {
                    $formFieldsTranslationData = $formData['languages'][$language['code']];
                    $formFieldsTranslationData['ffid'] = $last_id;
                    $formFieldsTranslationData['lid']  = $language['lid']; 
                      
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'fid'            =>   array('type' => 'keyword', 'value' => $data['fid']),   
                        'label'          =>   $formFieldsTranslationData['label'],
                        'description'    =>   $formFieldsTranslationData['description']
                    );
                    
                    $cmsSearch->addDocument($fields, $last_id);

                    $formsFieldsTranslationModels->insert($formFieldsTranslationData);
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
     * Edycja pól formularza
     */
    
    public function editField ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $data = array(
                'name'            =>    str_replace('-', '_', $formData['name']),
                'fid'             =>    $formData['fid'],
                'filters'         =>    (isset($formData['filters']) ? implode(',', $formData['filters']) : null),
                'validators'      =>    (isset($formData['validators']) ? implode(',', $formData['validators']) : null),
                'type'            =>    $formData['type'],
                'is_multilingual' =>    $formData['is_multilingual'],
                'is_deleted'      =>    0
            );
            
            if($this->isFieldUnique($data['name'], $data['fid'], $id) == false)
            {
                throw new Cms_Form_Exception('Nazwa pola formularza musi być unikalna.');
            }

            $this->update($data, $this->getAdapter()->quoteInto('ffid = ?', $id));

            $formsFieldsTranslationModels = new Models_FormsFieldsTranslation;
            $languagesModels              = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Forms-Fields'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();   
            if($languages)
            {
                foreach($languages as $language)
                {
                    $formFieldsTranslationData = $formData['languages'][$language['code']];
                    $formFieldsTranslationData['lid'] = $language['lid']; 

                    $where = array(
                        $this->getAdapter()->quoteInto('ffid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($formsFieldsTranslationModels->fetchRow($where))
                    {
                        $formsFieldsTranslationModels->update($formFieldsTranslationData, $where);
                    }
                    else
                    {
                        $formFieldsTranslationData['ffid'] = $id;
                        $formsFieldsTranslationModels->insert($formFieldsTranslationData);
                    }    
                    
                    $fields = array(
                        'name'           =>   $data['name'],   
                        'fid'            =>   array('type' => 'keyword', 'value' => $data['fid']),   
                        'label'          =>   $formFieldsTranslationData['label'],
                        'description'    =>   $formFieldsTranslationData['description']
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
    
    /**
     * Usunięcie pól formularza
     */
    
    public function deleteFields ($fields)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            if(is_array($fields))
            {
                $where = $this->getAdapter()->quoteInto('ffid IN(?)', $fields);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('ffid = ?', $fields);
            }

            $this->delete($where);

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }     

    /*
     * Sortowanie pól formularza
     */
    
    public function sortFields ($fields)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            if(is_array($fields))
            {
                foreach($fields as $field)
                {
                    $where = $this->getAdapter()->quoteInto('ffid = ?', $field['ffid']);
                    $this->update(array('sort' => $field['sort']), $where);
                }
            }
            else
            {
                return false;
            }

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    
    /*
     * Sprawdzanie czy nazwa pola jest unikalna
     */
    
    public function isFieldUnique ($name, $form_id, $id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('ff' => $this->_name))
                       ->where('ff.name = ?', $name);

        if($id != null)
        {
            $select->where('ff.ffid <> ?', $id);
        }

        $select->where('ff.fid = ?', $form_id);  

        if($this->fetchAll($select)->count() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }     
    
}
?>
