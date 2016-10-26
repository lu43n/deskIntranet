<?php

class Cms_Search
{
    public $index = null;
    
    public function find ($keyword)
    {
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('UTF-8');
        $query = Zend_Search_Lucene_Search_QueryParser::parse($keyword);

        $results = $this->getIndex()->find($query);
        
        $elements = null;
        foreach ($results as $result)
        {
            $elements[] = $result->index_id;
        }
        
        return $elements;
    }
    
    public function setIndex (Zend_Search_Lucene_Interface $index)
    {
        $this->index = $index;
        
        return $this;
    }
    
    public function getIndex ()
    {
        return $this->index;
    }
    
    public function addDocument ($fields, $id)
    {
        $document = new Zend_Search_Lucene_Document();       
        $document->addField(Zend_Search_Lucene_Field::keyword('index_id', $id));
                    
        foreach($fields as $fieldName => $fieldValue)
        {
            if(is_array($fieldValue))
            {
                $document->addField(Zend_Search_Lucene_Field::$fieldValue['type']($fieldName, $fieldValue['value'], 'UTF-8'));
            }
            else
            {
                $document->addField(Zend_Search_Lucene_Field::unStored($fieldName, $fieldValue, 'UTF-8'));
            }
        }
        
        $this->getIndex()->addDocument($document);
        $this->getIndex()->commit();
    }
    
    public function deleteDocument ($id, $column = null)
    {
        if($column == null)
        {
            $column = 'index_id';
        }
        
        if(is_array($id))
        {
            foreach($id as $index_id)
            {
                foreach ($this->getIndex()->find($column.':'.$index_id) as $result)
                {
                    $this->getIndex()->delete($result->id);
                }
            }
        }
        else
        {
            foreach ($this->getIndex()->find($column.':'.$id) as $result)
            {
                $this->getIndex()->delete($result->id);
            }
        }
    }
}

?>
