<?php

class Cms_Form_Element_Radio extends Zend_Form_Element_Radio
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
}
