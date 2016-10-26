<?php

class Admin_ProfileController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-profile');

    }
    
    public function indexAction ()
    {
        $usersModels = new Models_Users;
        
        $user = $usersModels->getUser(array('uid' => $this->identity->uid));

        if(!$user)
        {
            $this->addFlashMessage('Użytkownik nie zostało znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'login', 'action' => 'logout'), 'admin', true);
        } 
        
        $cmsForm = new Cms_Form('intranet-users-profile');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');

        $cmsForm->populate($user);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    
                    $usersModels->editProfile($formData, $this->identity->uid);
                    $cmsForm->addMessage('Profil użytkownika został zapisany.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas edycji profilu użytkownika.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }

        if($user)
        {
            $this->view->user  = $user;
        }
        
        $this->view->cmsForm = $cmsForm;
    }
}

