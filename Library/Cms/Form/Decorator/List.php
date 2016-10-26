<?php

class Cms_Form_Decorator_List extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
                                
        $view->name       = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();        
        $view->values     = json_decode($form->getValue(), true);     
        
        $view->element_id = substr(md5(time().rand(0,999)), 0, 4);
                
        return $view->render('_forms' . DS . 'list.twig');
    }
}
