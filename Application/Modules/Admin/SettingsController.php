<?php

class Admin_SettingsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-settings');
    }
    
    public function indexAction()
    {
        $settingsModels = new Models_Settings;        
        $this->view->settingsGroups = $settingsModels->getSettingsGroups();
    }
    
    public function editAction ()
    {
        $settingsModels = new Models_Settings;        
        $id = $this->_getParam('id', null);
        
        $settingsGroup = $settingsModels->getSettingsGroup($id);
        
        if(!$settingsGroup)
        {
            $this->addFlashMessage('Grupa ustawień nie została znaleziona!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'settings'), 'admin', true);
        }
        
        $settingsForm = new Cms_Form($settingsGroup['slug']);
        
        foreach($settingsForm->getElements() as $element)
        {
            $fieldNames[] = $element->getName();
        }
        
        $settingsForm->populate($settingsModels->getSettingsValues($fieldNames));

        if($this->getRequest()->isPost())
        {
            if($settingsForm->isValid($_POST))
            {    
                try
                {                    
                    $settingsModels->editSettings($settingsForm->getValues());
                    $settingsForm->addMessage('Ustawienia zostały zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Exception $e)
                {
                    $settingsForm->addMessage('Błąd podczas zapisu ustawień.', Cms_Form::SUCCESS);
                }
            }

            if($settingsForm->isErrors())
            {
                $settingsForm->populate($_POST);
            }
        }

        $this->view->settingsGroup = $settingsGroup;
        $this->view->settingsForm = $settingsForm;
    }
}

