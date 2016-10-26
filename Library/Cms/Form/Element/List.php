<?php

class Cms_Form_Element_List extends Zend_Form_Element_Hidden
{    
    public $_isArray = true;
    
    public function getAttribs ()
    {
        $attribs = parent::getAttribs();
        
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        return $attribs;
    }
    
    public function getValue() 
    {
        return parent::getValue();
    }
    
    public function setValue($values) 
    {
        if(is_array($values) && count($values) > 0)
        {
            $options = array();
            foreach($values as $value)
            {
                $value                           = explode(":", $value);
                $options[urldecode($value[1])] = urldecode($value[0]);
            }
            
            return parent::setValue(json_encode($options));
        }
        
        if($values == NULL)
        {
            $values = new Zend_Db_Expr('NULL');
        }
        
        return parent::setValue($values);
    }
    
}
