<?php

class Cms_Form_Decorator_Datetime extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form    = $this->getElement();
        $view    = $form->getView();
        
        unset($this->helper);
        
        $view->label = $form->getLabel();
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
        $view->value = $form->getValue();

        return $view->render('_forms' . DS . 'datetime.twig');
    }
}
