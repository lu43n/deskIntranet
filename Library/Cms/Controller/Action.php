<?php

class Cms_Controller_Action extends Zend_Controller_Action
{
    public $messages = array();
    
    public function init()
    {       
        $this->acl = $this->view->acl =  Zend_Registry::get('acl');
        $this->identity = $this->view->identity = Zend_Auth::getInstance()->getIdentity();
        $this->flashMessage = $this->getHelper('FlashMessenger')->setNamespace(CMS_DOMAIN . '.messages');
        $this->session = new Zend_Session_Namespace(CMS_DOMAIN.'.session');
        
        $this->view->request = $this->getRequest();
    }
    
    public function addFlashMessage ($message = '', $type = Cms_Form::SUCCESS)
    {
        $this->flashMessage->addMessage(array('type' => $type, 'message' => $message));
    }
    
    public function log ($message, $priority, $extras = null)
    {
        $logger = Zend_Registry::get('logger');
        $logger->log($message, $priority, $extras);
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
    
    /**
     * Przekierowanie
     *
     * @param  array $urlOptions Opcje potrzebne do przekierowania (akcja, kontroler, moduł, parametry)
     * @param  mixed $name Nazwa ścieżki (admin, default)
     * @param  bool $reset Resetować aktualne parametry?
     * @return void
     */
    
    public function redirect (array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        return $this->getHelper('redirector')->gotoRoute($urlOptions, $name, $reset, $encode);
    }
    
    public function addMessage ($message = '', $type = Cms_Form::SUCCESS)
    {
        $this->messages[] = array('type' => $type, 'message' => $message);
    }
    
    public function postDispatch() 
    {
        $this->view->flashMessages = $this->flashMessage->getMessages() + $this->messages;

        $this->session->lastUrl = $this->getRequest()->getRequestUri();
    }
}

?>
