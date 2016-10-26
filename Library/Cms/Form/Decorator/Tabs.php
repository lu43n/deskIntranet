<?php

class Cms_Form_Decorator_Tabs extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        unset($this->helper);
        
        $view->tabs = $form->getSubForms();

        return $view->render('_forms' . DS . 'tabs.twig');
    }
}
