<?php
class Models_GroupsTranslation extends Cms_Db
{
    protected $_name = 'groups_translation';
    protected $_primary = 'gtid';
        
        
    public function getTranslations ($id = null)
    {
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('gt' => $this->_name))
                   ->joinLeft(array('g' => 'groups'), 'g.gid = gt.gid')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = gt.lid', array('language_code' => 'l.code'))
                   ->where('g.is_deleted = 0')
                   ->where('gt.gid = ?', $id);

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'gtid'         => $result['gtid'],
                    'gid'          => $result['gid'],
                    'title'        => $result['title']
                );
            }
        }

        return (isset($translations) ? $translations : false);
    }     
}
?>
