<?php

class Cms_Form_Decorator_Tab extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        unset($this->helper);
        
        $view->messages = $form->getMessages();
        
        $view->content = $content;
                
        return $view->render('_forms' . DS . 'tab.twig');
    }
}
