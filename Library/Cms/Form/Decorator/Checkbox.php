<?php

class Cms_Form_Decorator_Checkbox extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
                        
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
        $view->checked = $form->isChecked();
                
        return $view->render('_forms' . DS . 'checkbox.twig');
    }
}
