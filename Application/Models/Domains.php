<?php
class Models_Domains extends Cms_Db
{
	protected $_name = 'domains';
	protected $_primary = 'did';
        
    /**
     * Wyszukujemy domenę po nazwie i ssl
     * 
     * @param type $name
     * @param type $ssl 
     * 
     * @return array or object
     */    
        
    public function getDomain ($name, $ssl)
    {
        if($ssl == true)
        {
            $where = new Zend_Db_Expr("REPLACE(`ssl`, 'www.', '') = ?");
        }
        else
        {
            $where = new Zend_Db_Expr("REPLACE(`url`, 'www.', '') = ?");
        }
        
        $select = $this->select()->from(array('d' => $this->_name))->where($where, $name);
        
        return $this->fetchRow($select);
    }
}
?>
