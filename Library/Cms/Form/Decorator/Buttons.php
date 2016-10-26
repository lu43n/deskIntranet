<?php

class Cms_Form_Decorator_Buttons extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        unset($this->helper);

        $view->elements = $form->getElements();

        return $view->render('_forms' . DS . 'buttons.twig');
    }
}
