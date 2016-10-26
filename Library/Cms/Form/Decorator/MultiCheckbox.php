<?php

class Cms_Form_Decorator_MultiCheckbox extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();  
                
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
        $view->values = $form->getValue();  
        $view->options = $this->getElement()->options;
        
        return $view->render('_forms' . DS . 'multicheckbox.twig');
    }
}
