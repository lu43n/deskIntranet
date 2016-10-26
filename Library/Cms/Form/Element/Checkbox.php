<?php

class Cms_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
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
        
        if(isset($attribs['checked']))
        {
            unset($attribs['checked']);
        }
        
        return $attribs;
    }
}
