<?php

class Cms_Form_Decorator_Element extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form    = $this->getElement();
        $view    = $form->getView();
        
        unset($this->helper);
        
        $view->name = $form->getFullyQualifiedName();
        $view->label = $form->getLabel();
        $view->description = $form->getDescription();
        $view->attributes = $form->getAttribs();
        $view->value = $form->getValue();
        
        $view->required = $form->isRequired();
                
        $view->content = $content;
        
        if($form instanceof Cms_Form_Element_Submit || $form instanceof Cms_Form_Element_Button)
        {
            return $view->render('_forms' . DS . 'buttons.twig');
        }

        return $view->render('_forms' . DS . 'element.twig');
    }
}
