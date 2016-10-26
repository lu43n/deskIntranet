<?php
class Models_EventsTranslation extends Cms_Db
{
    protected $_name = 'events_translation';
    protected $_primary = 'etid';
    protected $_referenceMap = array(
        "Dictionary" => array(
            "columns" => array("eid"),
            "refTableClass" => "Models_Events",
            "refColumns" => array("eid")
        ),
        "Languages" => array(
            "columns" => array("lid"),
            "refTableClass" => "Models_Languages",
            "refColumns" => array("lid")
        )
    );
   
    public function getEventsTranslations ($options = array())
    {
        $translations = array();
        
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('et' => $this->_name))
                   ->joinLeft(array('e' => 'events'), 'e.eid = et.eid')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = et.lid', array('language_code' => 'l.code'));
        
        if($options['eid'])
        {
            $select->where('et.eid = ?', $options['eid']);
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'etid'         => $result['etid'],
                    'neid'         => $result['eid'],
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
