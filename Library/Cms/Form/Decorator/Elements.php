<?php

class Cms_Form_Decorator_Elements extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $form           = $this->getElement();
        $view           = $form->getView();
        $translator     = $form->getTranslator();
        $displayGroups  = ($form instanceof Zend_Form) ? $form->getDisplayGroups() : array();
        $belongsTo      = ($form instanceof Zend_Form) ? $form->getElementsBelongTo() : null;
        $separator      = $this->getSeparator();

        $view->name       = $form->getFullyQualifiedName();
        $view->attributes = $form->getAttribs();
                
        foreach ($form as $item) 
        {            
            $item->setView($view)
                 ->setTranslator($translator);
            if ($item instanceof Zend_Form_Element) {
                foreach ($displayGroups as $group) {
                    $elementName = $item->getName();
                    $element     = $group->getElement($elementName);
                    if ($element) {
                        // Element belongs to display group; only render in that
                        // context.
                        continue 2;
                    }
                }
                $item->setBelongsTo($belongsTo);
            } elseif (!empty($belongsTo) && ($item instanceof Zend_Form)) {
                if ($item->isArray()) {
                    $name = $this->mergeBelongsTo($belongsTo, $item->getElementsBelongTo());
                    $item->setElementsBelongTo($name, true);
                } else {
                    $item->setElementsBelongTo($belongsTo, true);
                }
            } elseif (!empty($belongsTo) && ($item instanceof Zend_Form_DisplayGroup)) {
                foreach ($item as $element) {
                    $element->setBelongsTo($belongsTo);
                }
            }

            $items[] = $item->render();
        }
        
        if(isset($items))
        {
            $elementContent = implode($separator, $items);

            $view->content = $elementContent;
        }
        return $view->render('_forms' . DS . 'elements.twig');
    }
}