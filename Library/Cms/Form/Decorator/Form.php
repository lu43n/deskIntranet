<?php

class Cms_Form_Decorator_Form extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();

        $view->method = $form->getMethod();
        $view->action = $form->getAction();

        $view->content = $content;

        return $view->render('_forms' . DS . 'form.twig');
    }
}