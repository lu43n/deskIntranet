<?php
class Models_FormsFieldsTranslation extends Cms_Db 
{
    protected $_name = 'forms_fields_translation';
    protected $_primary = 'fftid';
    protected $_referenceMap = array(
        "Forms" => array(
            "columns" => array("fid"),
            "refTableClass" => "Models_Forms",
            "refColumns" => array("fid")
        )
    );

    public function getTranslations ($id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('fft' => $this->_name))
                       ->joinLeft(array('ff' => 'forms_fields'), 'ff.ffid = fft.ffid')
                       ->joinLeft(array('l' => 'languages'), 'l.lid = fft.lid', array('language_title' => 'l.title', 'language_code' => 'l.code'))
                       ->where('ff.is_deleted = 0')
                       ->where('fft.ffid = ?', $id);

        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $translations[$result['language_code']] = array(
                    'fftid'         => $result['fftid'],
                    'ffid'          => $result['ffid'],
                    'description'  => $result['description'],
                    'label'        => $result['label'],
                    'default'      => $result['default'],
                    'options'      => $result['options']
                );
            }
        }
        
        return (isset($translations) ? $translations : false);
    }     
}
?>
