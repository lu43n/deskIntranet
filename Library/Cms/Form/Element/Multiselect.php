<?php

class Cms_Form_Element_Multiselect extends Zend_Form_Element_Multiselect
{
    public $helper = '';   
    public $multiple = 'multiple';
    protected $_isArray = true;
}
