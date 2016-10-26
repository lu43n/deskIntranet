<?php

class Cms_Validate_Form_Unique extends Zend_Validate_Abstract
{
    const NOT_UNIQUE = 'notUnique';
    
    public $options = array();
 
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => 'Pole "%s" musi być unikalne w danej grupie elementów.'
    );
    
    public function __construct ($options = array()) 
    {
        $this->options = $options;
    }
 
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
        
        
        if(isset($context['parent_id']))
        {
            $
        }
        
        $this->_error(self::IN_PARENT);
        return false;        
    }
}

?>
