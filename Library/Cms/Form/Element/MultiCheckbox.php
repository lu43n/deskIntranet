<?php

class Cms_Form_Element_MultiCheckbox extends Zend_Form_Element_MultiCheckbox
{
    public function getAttribs ()
    {
        $attribs = parent::getAttribs();
        
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        if(isset($attribs['options']))
        {
            unset($attribs['options']);
        }
        
        return $attribs;
    }
    
    public function setValue($value) 
    {
        $values = parent::setValue($value);
        
        if(is_array($value) == false && !empty($value))
        {
            $values = explode(',', $value);
        }
        
        return $values;
    }
}
