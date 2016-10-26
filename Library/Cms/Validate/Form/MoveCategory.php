<?php

class Cms_Validate_Form_MoveCategory extends Zend_Validate_Abstract
{
    const IN_PARENT = 'inParent';
    
    public $options = array();
 
    protected $_messageTemplates = array(
        self::IN_PARENT => 'Formularz nie może być przeniesiony do nadrzędnej kategorii.'
    );
    
    public function __construct ($options = array()) 
    {
        $this->options = $options;
    }
 
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
        $formsGroupsModels = new Models_FormsGroups();
 
        if (is_array($context)) 
        {                        
            $parentCategory = $formsGroupsModels->getCategory($context['parent_id']);

            $paths = explode('.', $parentCategory['path']);
                                
            if(is_array($paths) && in_array($this->options['fgid'], $paths) && $parentCategory['depth'] > $this->options['depth'])
            {
                $this->_error(self::IN_PARENT);
                return false;
            }
            elseif($this->options['fgid'] == $context['parent_id'])
            {
                $this->_error(self::IN_PARENT);
                return false;
            }
            else
            {
                return true;
            }
        } 
 
        return false;
    }
}

?>
