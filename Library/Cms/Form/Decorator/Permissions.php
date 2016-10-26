<?php

class Cms_Form_Decorator_Permissions extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();  
        
        $view->name = $form->getName();
        $view->attributes = $form->getAttribs();
                
        $view->element = $form;

        $view->values = $form->getValue();  
        $view->options = $form->options;
                        
        return $view->render('_forms' . DS . 'permissions.twig');
    }
}
