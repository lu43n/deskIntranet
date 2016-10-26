<?php

class Cms_Form_Element_Permissions extends Zend_Form_Element_MultiCheckbox
{
    public function isValid($value, $context = null)
    {
        $this->addValidator(
            'InArray',
            true,
            array(array('','0', '1'))
        );

        return parent::isValid($value, $context);
    }

}
