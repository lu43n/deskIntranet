<?php

class Admin_LoginController extends Cms_Controller_Action_Admin
{
    public function indexAction()
    {        
        if($this->identity)
        {
            $this->_forward('index', 'index');
        }
        
        $form = new Cms_Form('intranet-login');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post');
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {   
                $auth = new Cms_Auth($form->getValue('username'), $form->getValue('password'), new Models_Users);
                          
                $result = $auth->authenticate();
                                
                if($result->getCode() == Zend_Auth_Result::SUCCESS)
                {
                    $auth->saveIdentity($result->getIdentity());
                    $form->addMessage($this->translate('[admin-login] Zalogowano pomyślnie!'), Cms_Form::SUCCESS);  
                 
                    $this->_helper->redirector->gotoUrl($form->getAction());
                }
                else
                {
                    $form->addMessage($this->translate('[admin-login] Niepoprawna nazwa użytkownika lub hasło.'), Cms_Form::ERROR);  
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }
        
        $auth = Zend_Auth::getInstance();
                
        $this->view->loginForm = $form;
    }
    
    public function logoutAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->goToRoute(array('controller' => 'index', 'action' => 'index'), 'admin');
    }


}

