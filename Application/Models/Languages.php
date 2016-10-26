<?php
class Models_Languages extends Cms_Db
{
    protected $_name = 'languages';
    protected $_primary = 'lid';
        
    public function getLanguages()
    {
        return $this->fetchAll(array('is_active' => '1'), 'sort ASC');
    }
    
    public function getDefault()
    {
        return $this->fetchRow(array('is_default' => '1'), 'sort ASC');
    }

    public function getIdFromCode($code)
    {
        $where = $this->getAdapter()->quoteInto('code = ?', $code);
        
        return $this->fetchRow($where);
    }
    
    public function isAvaliable($code)
    {
        $where = $this->getAdapter()->quoteInto('code = ?', $code);
        
        return (bool) $this->fetchRow($where);
    }
}
?>
