<?php
class Models_Dictionary extends Cms_Db
{
    protected $_name = 'dictionary';
    protected $_primary = 'did';
    protected $_dependentTables = array(
        "Models_DictionaryTranslation"
    );

    public function getWords ($options = array())
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name));

        if(isset($options['did']))
        {
            if(is_array($options['did']))
            {
                $select->where('did IN (?)', $options['did']);
            }
        }
        
        $results = $this->fetchAll($select);

        $words = array();
        if($results)
        {
            foreach($results as $result)
            {
                $words[$result['did']] = array(
                    'did'                =>   $result['did'],
                    'key'                =>   $result['key']
                );
            }
        }

        return $words;
    }
    
    public function getWord ($options, $language = LOCALE_ID)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('d' => $this->_name))
                       ->joinLeft(array('dt' => 'dictionary_translation'), 'dt.did = d.did AND dt.lid = "'.$language.'"', array('value' => 'dt.value'));

        if(isset($options) && is_array($options))
        {
            if(isset($options['did']))
            {
                $select->where('d.did = ?', $options['did']);
            }
        }

        $result = $this->fetchRow($select);

        $word = array();
        if($result)
        {
            $result = $result->toArray();

            $word = array(
                'did'         =>   $result['did'],
                'key'         =>   $result['key'],
                'translation' =>   array(
                    'value'       =>   $result['value'],
                )
            );
        }

        return $word;
    }

    public function addWord ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $wordData = array(
                'key'  =>  $formData['key']
            );

            $last_id = $this->insert($wordData);

            $dictionaryTranslationModels = new Models_DictionaryTranslation;
            $languagesModels             = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Dictionary'));

            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $wordTranslationData = $formData['languages'][$language['code']];
                    $wordTranslationData['did']  = $last_id;
                    $wordTranslationData['lid']  = $language['lid'];

                    $dictionaryTranslationModels->insert($wordTranslationData);             

                    $fields = array(
                        'key'     =>   $wordData['key'],   
                        'value'   =>   $wordTranslationData['value']
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
    

    public function editWord ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $wordData = array(
                'key' => $formData['key']
            );

            $where = $this->getAdapter()->quoteInto('did = ?', $id);
            $this->update($wordData, $where);

            // Zapisywanie translacji

            $dictionaryTranslationModels = new Models_DictionaryTranslation;
            $languagesModels             = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Dictionary'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $wordTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('did = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($dictionaryTranslationModels->fetchRow($where))
                    {
                        $dictionaryTranslationModels->update($wordTranslationData, $where);
                    }
                    else
                    {
                        $data['lid']  = $language['lid'];
                        $data['did']  = $id;
                        $dictionaryTranslationModels->insert($data);
                    }           

                    $fields = array(
                        'key'     =>   $wordData['key'],   
                        'value'   =>   $wordTranslationData['value']
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

    public function deleteWords ($words)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Dictionary'));
            
            if(is_array($words))
            {
                $where = $this->getAdapter()->quoteInto('did IN(?)', $words);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('did = ?', $words);
            }

            $this->delete($where);

            $cmsSearch->deleteDocument($words);
            
            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
   
}
?>
