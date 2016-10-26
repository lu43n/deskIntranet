<?php

class Cms_Form_SubForm extends Cms_Form
{
    protected $_isArray = true;
    
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
        $errors = $messages = array();
        
        if (null !== $name) 
        {
            if (isset($this->_elements[$name])) 
            {
                return $this->getElement($name)->getErrors();
            } 
            elseif (isset($this->_subForms[$name])) 
            {
                $errors = $this->getSubForm($name)->getMessages(null, true);
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
                    $messages[] = $this->translate($message, $this->getElement($field)->getLabel());
                }
            }
        }        

        return array('errors' => $messages);
    }
    
    
}

?>