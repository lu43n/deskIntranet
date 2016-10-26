<?php

class Cms_Form_Decorator_Select extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        unset($this->helper);
        
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
        $view->value = $form->getValue();
        $view->options = $this->getElement()->options;
        
        return $view->render('_forms' . DS . 'select.twig');
    }
}
