<?php

class Cms_Form_Element_Files extends Zend_Form_Element_Hidden
{        
    protected $_isArray = true;
    
    public function getAttribs ()
    {
        $attribs = parent::getAttribs();
        
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        return $attribs;
    }

    public function setValue($values) 
    {
        if(is_array($values) && count($values) > 0)
        {
            $options = array();
            foreach($values as $value)
            {
                if(is_array($value))
                {
                    $name = $value['name']; $url = $value['url'];
                }
                else 
                {
                    $value = explode(",", $value);
                    $name = $value[0]; $url = $value[1];
                }
                
                $options[] = array('name' => urldecode($name), 'url' => urldecode($url));
            }
            
            return parent::setValue(json_encode($options));
        }
        
        return parent::setValue($values);
    }
    
}
