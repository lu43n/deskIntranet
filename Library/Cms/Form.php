<?php
/**
 * deskCMS
 * 
 * @copyright Copyright (c) 2012
 * @version 1.0
 * @author deskCMS Team
 * @see http://deskcms.pl/
 * 
 */

class Cms_Form extends Zend_Form
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const ALERT = 'alert';
    const INFO = 'info';
    public $messages = array(self::SUCCESS => array(), self::ERROR => array(), self::ALERT => array(), self::INFO => array());
    public $formName = null;
    
    /**
     *
     * @param string $formName
     * @param bool $enableDecorators
     */

    public function  __construct($formName = null)
    {
        if($formName !== null && !is_array($formName))
        {
            $this->formName = $formName;
        }
        
        $this->setDisableLoadDefaultDecorators(true)
             ->setDecorators(array('Elements','Frame','Form'))
             ->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', 'decorator')
             ->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element', 'element');

        parent::__construct($formName);
    }
    
    public function disableForm ($flag = true)
    {
        if($flag == true)
        {
            $this->removeDecorator('Form');
        }
        else
        {
            if(!$this->getDecorator('Form'))
            {
                $this->addDecorator('Form');
            }
        }
        
        return $this;
    }

    public function init ()
    {
        $formsFieldsModels = new Models_FormsFields;
        $languagesModels = new Models_Languages;
        
        $languages = $languagesModels->fetchAll(array('is_active' => '0'), 'sort ASC');
        
        $fields = false;
        $multilingualElements = array();

        if($this->formName != null)
        {
            $fields = $formsFieldsModels->getFields(array('slug' => $this->formName));
        }

        if((bool) $fields == false)
        {
            return;
        }

        $element = null;
        foreach($fields as $field)
        {
            if($field['is_multilingual'] == 1)
            {
                $multilingualElements[] = $field;
            }
            else
            {          
                $element = $this->createElement($field['type'], $field['name']);
                $element->setDecorators(array($field['type'], 'Element'));
                $this->setField($element, $field);

                
                if(in_array($field['type'], array('submit', 'button')))
                {
                    $buttonsElements[] = $field;
                }
                else
                {
                    $this->addElement($element);                
                }
            }
        }
                
        if(isset($buttonsElements) && count($buttonsElements) > 0)
        {            
            $buttonsSubform = new Cms_Form_SubForm;
            $buttonsSubform->setOrder(999)
                           ->setDisableLoadDefaultDecorators(true)
                           ->setDecorators(array('Buttons'))
                           ->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', 'decorator')
                           ->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element', 'element');
            
            foreach($buttonsElements as $field)
            {
                $element = $this->createElement($field['type'], $field['name']);
                $element->setDecorators(array($field['type']));
                $this->setField($element, $field);
                $buttonsSubform->addElement($element);
            }

            $this->addSubform($buttonsSubform, 'buttons');
        }
        

        if(count($multilingualElements) > 0)
        {
            $subFormLanguages = new Cms_Form_SubForm;
            $subFormLanguages->setDisableLoadDefaultDecorators(true)
                             ->setDecorators(array('Tabs'))
                             ->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', 'decorator')
                             ->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element', 'element');
            
            foreach($languages as $language)
            {
                $subForm = new Cms_Form_SubForm;
                $subForm->setAttrib('id', rand(0,99));
                $subForm->setAttrib('title', $language->title);
                $subForm->setDisableLoadDefaultDecorators(true)
                        ->setDecorators(array('Elements', 'Tab'))
                        ->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', 'decorator')
                        ->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element', 'element');
                
                foreach($multilingualElements as $field)
                {         
                    $element = $this->createElement($field['type'], $field['name']);
                    $element->setDecorators(array($field['type'], 'Element'));

                    $this->setField($element, $field);          
  
                    $subForm->addElement($element);
                }

                $subFormLanguages->addSubForm($subForm, $language->code);
            }

            $this->addSubForm($subFormLanguages, 'languages');
        }
    }
    
    public function setField ($element, $options)
    {
            if(!empty($options['translation']['label']))
            {
                $element->setLabel($options['translation']['label']);
            }

            if(!empty($options['filters']))
            {
                $filters = explode(',', $options['filters']);
                $filtersClasses = array();
                
                foreach($filters as $filter)
                {
                    $filterClassName = 'Cms_Filter_'.$filter;
                    
                    if(class_exists($filterClassName))
                    {
                        $filtersClasses[] = new $filterClassName;
                    }
                }

                $element->addFilters($filtersClasses);
            }

            if(!empty($options['validators']))
            {
                $validators = explode(',', $options['validators']);
                $validatorsClasses = array();

                foreach($validators as $validator)
                {
                    $validatorClassName = 'Cms_Validate_'.$validator;
                    
                    if(class_exists($validatorClassName))
                    {
                        $validatorsClasses[] = new $validatorClassName;
                    }
                    
                    if($validator == 'required')
                    {
                        $element->setRequired(true);
                    }
                }

                $element->addValidators($validatorsClasses);
            }

            if(!empty($options['translation']['description']))
            {
                $element->setDescription($options['translation']['description']);
            }
            
            if(in_array($options['type'], array('select', 'multiselect', 'multicheckbox', 'radio')) && !empty($options['translation']['options']))
            {
                $element->setMultiOptions(json_decode($options['translation']['options'], true));
            }

            if(!empty($options['translation']['default']))
            {
                $element->setValue($options['translation']['default']);
            }
            
            if(in_array($options['type'], array('submit', 'button')))
            {
                $element->setOrder(999);
            }
                        
            return $element;
    }
    
    public function addMessage ($message = '', $type = Cms_Form::SUCCESS)
    {
        if($type == self::ERROR)
        {
            $this->markAsError();
        }
        
        $this->messages[$type][] = $message;
    }

    public function translate ($messageid)
    {
        if ($messageid === null) {
            return $this;
        }

        $translate = Zend_Registry::get('Zend_Translate');
        $options   = func_get_args();

        array_shift($options);
        $count  = count($options);
        $locale = null;
        if ($count > 0) {
            if (Zend_Locale::isLocale($options[($count - 1)], null, false) !== false) {
                $locale = array_pop($options);
            }
        }

        if ((count($options) === 1) and (is_array($options[0]) === true)) {
            $options = $options[0];
        }

        if ($translate !== null) {
            $messageid = $translate->translate($messageid, $locale);
        }

        if (count($options) === 0) {
            return $messageid;
        }

        return vsprintf($messageid, $options);
    }
    
    public function getMessages ($name = null)
    {
        $errors = array();
        if (null !== $name) {
            if (isset($this->_elements[$name])) 
            {
                return $this->getElement($name)->getErrors();
            } 
            elseif (isset($this->_subForms[$name])) 
            {
                $errors = $this->getSubForm($name)->getErrors(null, true);
            }
        }
        else
        {
            foreach ($this->_elements as $key => $element) 
            {
                $errors[$key] = $element->getMessages();
            }
        }

        foreach($errors as $field => $errorTypes)
        {
            if(count($errorTypes) > 0)
            {
                foreach($errorTypes as $errorType => $message)
                {
                    $this->messages[self::ERROR][] = $this->translate($message, $this->getElement($field)->getLabel());
                }
            }
        }
        
        if(count($this->messages[self::ERROR]) > 0)
        {
            $this->markAsError();
        }
        
        return array('errors' => $this->messages[self::ERROR], 'success' => $this->messages[self::SUCCESS], 'alerts' => $this->messages[self::ALERT], 'info' => $this->messages[self::INFO]);
    }
}

?>