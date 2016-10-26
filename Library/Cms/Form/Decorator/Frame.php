<?php

class Cms_Form_Decorator_Frame extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        $view->name = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();

        $view->messages = $form->getMessages();
        
        $view->content = $content;

        return $view->render('_forms' . DS . 'frame.twig');
    }
}