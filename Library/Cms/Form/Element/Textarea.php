<?php

class Cms_Form_Element_Textarea extends Zend_Form_Element_Textarea
{
    public function getAttribs ()
    {
        $attribs = parent::getAttribs();
        
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        return $attribs;
    }
}
