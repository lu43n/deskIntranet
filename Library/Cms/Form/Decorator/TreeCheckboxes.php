<?php

class Cms_Form_Decorator_TreeCheckboxes extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();  
                
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
                
        $view->values = $form->getValue();  
        $view->options = $form->options;
                
        return $view->render('_forms' . DS . 'treeCheckboxes.twig');
    }
}
