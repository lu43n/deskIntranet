<?php
class Models_DictionaryTranslation extends Cms_Db
{
    protected $_name = 'dictionary_translation';
    protected $_primary = 'dtid';
    protected $_referenceMap = array(
        "Dictionary" => array(
            "columns" => array("did"),
            "refTableClass" => "Models_Dictionary",
            "refColumns" => array("did")
        ),
        "Languages" => array(
            "columns" => array("lid"),
            "refTableClass" => "Models_Languages",
            "refColumns" => array("lid")
        )
    );
   
    public function getWordTranslations ($options = array())
    {
        $translations = array();
        
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('dt' => $this->_name))
                   ->joinLeft(array('d' => 'dictionary'), 'd.did = dt.did')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = dt.lid', array('language_code' => 'l.code'));
        
        if($options['did'])
        {
            $select->where('dt.did = ?', $options['did']);
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'dtid'         => $result['dtid'],
                    'did'          => $result['did'],
                    'value'        => $result['value']
                );
            }
        }

        return $translations;
    }     
        
        public function getDictonary ($options = array())
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('dt' => 'dictionary_translation'), array('d.key', 'dt.value'))
                           ->joinLeft(array('d' => 'dictionary'), 'd.did = dt.did', array())
                           ->joinLeft(array('l' => 'languages'), 'dt.lid = l.lid', array());
            
            if(isset($options['language_code']) && !empty($options['language_code']))
            {
                $select->where('code = ?', $options['language_code']);
            }
            
            return $this->fetchAll($select);
        }
}
?>
