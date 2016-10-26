<?php
class Models_NewsTranslation extends Cms_Db
{
    protected $_name = 'news_translation';
    protected $_primary = 'ntid';
    protected $_referenceMap = array(
        "Dictionary" => array(
            "columns" => array("nid"),
            "refTableClass" => "Models_News",
            "refColumns" => array("nid")
        ),
        "Languages" => array(
            "columns" => array("lid"),
            "refTableClass" => "Models_Languages",
            "refColumns" => array("lid")
        )
    );
   
    public function getNewsTranslations ($options = array())
    {
        $translations = array();
        
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('nt' => $this->_name))
                   ->joinLeft(array('n' => 'news'), 'n.nid = nt.nid')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = nt.lid', array('language_code' => 'l.code'));
        
        if($options['nid'])
        {
            $select->where('nt.nid = ?', $options['nid']);
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'ntid'         => $result['ntid'],
                    'nid'          => $result['nid'],
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
