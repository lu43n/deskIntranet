<?php
class Models_PermissionsTranslation extends Cms_Db
{
    protected $_name = 'permissions_translation';
    protected $_primary = 'ptid';
        
        
    public function getTranslations ($options = array())
    {
        $translations = array();
        
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('pt' => $this->_name))
                   ->joinLeft(array('p' => 'permissions'), 'p.pid = pt.pid')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = pt.lid', array('language_code' => 'l.code'));
        
        if($options['pid'])
        {
            $select->where('pt.pid = ?', $options['pid']);
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'ptid'         => $result['ptid'],
                    'pid'          => $result['pid'],
                    'title'        => $result['title']
                );
            }
        }

        return $translations;
    }     
}
?>
