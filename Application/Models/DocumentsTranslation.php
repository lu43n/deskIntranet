<?php
class Models_DocumentsTranslation extends Cms_Db 
{
    protected $_name = 'documents_translation';
    protected $_primary = 'dtid';
        
    public function getTranslations ($options = array())
    {
        $translations = array();
        
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('dt' => $this->_name))
                   ->joinLeft(array('d' => 'documents'), 'd.did = dt.did')
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
                    'title'        => $result['title'],
                    'attachments'  => $result['attachments'],
                    'content'      => $result['content']
                );
            }
        }

        return $translations;
    }    
}
?>
