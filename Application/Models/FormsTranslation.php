<?php
class Models_FormsTranslation extends Cms_Db
{
    protected $_name = 'forms_translation';
    protected $_primary = 'ftid';
    protected $_referenceMap = array(
        "FormsGroups" => array(
            "columns" => array("fid"),
            "refTableClass" => "Models_Forms",
            "refColumns" => array("fid")
        )
    );

    public function getTranslations ($id = null)
    {
        $select = $this->select()
                   ->setIntegrityCheck(false)
                   ->from(array('ft' => $this->_name))
                   ->joinLeft(array('f' => 'forms'), 'f.fid = ft.fid')
                   ->joinLeft(array('l' => 'languages'), 'l.lid = ft.lid', array('language_code' => 'l.code'))
                   ->where('f.is_deleted = 0')
                   ->where('ft.fid = ?', $id);

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'ftid'         => $result['ftid'],
                    'fid'          => $result['fid'],
                    'title'         => $result['title'],
                    'description'   => $result['description']
                );
            }
        }

        return (isset($translations) ? $translations : false);
    }     
}
?>
