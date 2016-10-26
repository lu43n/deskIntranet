<?php

class Cms_Form_Decorator_TreeSelect extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();  
                
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
                
        $view->value = $form->getValue();         
        $view->options = $form->options;
        
        return $view->render('_forms' . DS . 'treeSelect.twig');
    }
}
