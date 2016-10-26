<?php

class Admin_FormsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-forms');
    }
    
    public function indexAction ()
    {
        $formsModels = new Models_Forms;
        $id = $this->_getParam('id', null);
        
        $options = array();
        if($id)
        {
            $this->view->form = $formsModels->getForm(array('fid' => $id));
            $this->view->breadcrumbs = $formsModels->getBreadcrumbs($id);  
            
            $options['parent_id'] = $id;
        }
        else
        {
            $options['depth'] = 1;
        }
        
        $this->view->forms = $formsModels->getForms($options);
    }
    
    public function searchAction ()
    {
        $formsModels = new Models_Forms;
        $formsFieldsModels = new Models_FormsFields;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearchForms = new Cms_Search;
            $cmsSearchForms->setIndex(Cms_Search_Index::getIndex('Forms'));
            $resultsForms = $cmsSearchForms->find($keyword);

            if($resultsForms)
            {
                $this->view->forms = $formsModels->getForms(array('fid' => $resultsForms));
            }

            $cmsSearchFormsFields = new Cms_Search;
            $cmsSearchFormsFields->setIndex(Cms_Search_Index::getIndex('Forms-Fields'));
            $resultsFields = $cmsSearchFormsFields->find($keyword);

            if($resultsFields)
            {
                $this->view->fields = $formsFieldsModels->getFields(array('ffid' => $resultsFields));
            }
        }
        catch(Exception $e) { }
    }
    
    // Start formularze //

    public function addFormAction ()
    {
        $formsModels  = new Models_Forms;
                
        $id = $this->_getParam('id', null);
                
        $cmsForm = new Cms_Form('intranet-forms');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $treeForms = $formsModels->getTreeForms();
        
        $cmsForm->getElement('parent_id')->setMultiOptions($treeForms)->setValue($id);

        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formsModels->addForm($cmsForm->getValues());
                    $cmsForm->addMessage('Formularz został zapisany.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania formularza.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->form = $formsModels->getForm(array('fid' => $id));
            $this->view->breadcrumbs = $formsModels->getBreadcrumbs($id);
        }
        
        $this->view->cmsForm = $cmsForm;
    }
    
    public function editFormAction()
    {
        $formsModels = new Models_Forms;
        $formsTranslationModels = new Models_FormsTranslation;                
        
        $id = $this->_getParam('id', null);
        $form = $formsModels->getForm(array('fid' => $id));
        
        if(!$form)
        {
            $this->addFlashMessage('Brak rekordu o podanym identyfikatorze', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'forms', 'action' => 'index'), 'admin', true);
        }
        
        $cmsForm = new Cms_Form('intranet-forms');
                
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $formTranslations = $formsTranslationModels->getTranslations($id);
                
        $cmsForm->populate($form + $formTranslations);
        
        $treeForms = $formsModels->getTreeForms(array('exclude_childrens' => array('path' => $form['path'])));

        $cmsForm->getElement('parent_id')->setMultiOptions($treeForms);

        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                              
                try
                {
                    $data = $cmsForm->getValues();
                    $data['depth'] = $form['depth'];
                    $data['path']  = $form['path'];
                    $data['slug']  = $form['slug'];
                    
                    $formsModels->editForm($data, $id);                           
                    $cmsForm->addMessage('Formularz został zapisany.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas zapisu formularza.', Cms_Form::ERROR);
                }
            }
            
            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->form        = $form;
            $this->view->breadcrumbs = $formsModels->getBreadcrumbs($id);
        }
        
        $this->view->cmsForm = $cmsForm;
    }
    
    public function deleteFormsAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if(!$this->acl->isUserAllowed('intranet-forms'))
            {
                echo json_encode(array('message' => 'Nie posiadasz uprawnień do usuwania kategorii formularzy.', 'type' => 'error'));
                return;
            }
            
            $formsModels = new Models_Forms;
            $forms = $this->_getParam('id', null);
            
            $toDelete = array();
            
            if(count($forms) > 0)
            {
                foreach($forms as $form)
                {
                    $toDelete[] = $form['value'];
                }
            }
            
            if(!empty($toDelete))
            {
                try     
                {
                    $formsModels->deleteForms($toDelete);
                    $result = array('message' => 'Wybrane kategorie zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                }
                catch(Cms_Exception $e)
                {
                    $result = array('message' => 'Błąd podczas usuwania kategorii.', 'type' => 'error');
                }

            }
                        
            echo json_encode($result);
        }
    }
    
    // Koniec formularzy //
    
    // Start pola formularzy //
    
    public function viewFieldsAction()
    {
        $formsModels = new Models_Forms;
        $formsFieldsModels = new Models_FormsFields;
        $id = $this->_getParam('id', null);
                
        if($id)
        {
            $this->view->form = $formsModels->getForm(array('fid' => $id));
            $this->view->fields = $formsFieldsModels->getFields(array('fid' => $id));
            $this->view->breadcrumbs = $formsModels->getBreadcrumbs($id);       
        }
        else
        {
            $this->addFlashMessage('Pola formularza nie zostały znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'forms'), 'admin', true);
        }   
    }
      
      
    public function sortFieldsAction ()
    {        
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $formsFieldsModels = new Models_FormsFields;
            
            if($this->_getParam('id'))
            {
                $fields = $this->_getParam('id');
                for($i = 0; $i < count($fields); $i++)
                {                    
                    $data[] = array(
                        'ffid'  =>  $fields[$i]['value'],
                        'sort'  =>  ($i + 1)
                    );
                }
                                                
                $formsFieldsModels->sortFields($data);
            }
            
            $result = array('message' => 'Zapisano kolejność.', 'type' => 'success');

            echo json_encode($result);
        }
    }
    
    public function addFieldAction ()
    {
        $formsModels       = new Models_Forms;
        $formsFieldsModels = new Models_FormsFields;

        $id = $this->_getParam('id', null);
                
        $fieldForm = new Cms_Form('intranet-forms-field');
        $fieldForm->setAction($this->getRequest()->getRequestUri())
                  ->setMethod('post');
        
        $treeForms = $formsModels->getTreeForms(array('exclude_root' => true));
        $fieldForm->getElement('fid')->setMultiOptions($treeForms)->setValue($id);
        
        if($this->getRequest()->isPost())
        {
            if($fieldForm->isValid($_POST))
            {         
                try
                {                    
                    $formsFieldsModels->addField($fieldForm->getValues());
                    $fieldForm->addMessage('Pole formularza zostało zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $fieldForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $fieldForm->addMessage('Błąd podczas dodawania pola formularza.', Cms_Form::ERROR);
                }
            }
                        
            if($fieldForm->isErrors())
            {
                $fieldForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->form = $formsModels->getForm(array('fid' => $id));
            $this->view->breadcrumbs = $formsModels->getBreadcrumbs($id);
        }
        
        $this->view->fieldForm   = $fieldForm;      
    }
    
   
    public function editFieldAction ()
    {
        $formsModels = new Models_Forms;
        $formsFieldsModels = new Models_FormsFields;
        $formsFieldsTranslationModels = new Models_FormsFieldsTranslation;

        $id = $this->_getParam('id', null);
        $field = $formsFieldsModels->getField(array('ffid' => $id));
        
        if(!$field)
        {
            $this->addFlashMessage('Pole formularza nie zostało znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'forms'), 'admin', true);
        }   
        
        $fieldForm = new Cms_Form('intranet-forms-field');
        $fieldForm->setAction($this->getRequest()->getRequestUri())
                  ->setMethod('post');

        $fieldTranslations = $formsFieldsTranslationModels->getTranslations($id);
        $fieldForm->populate(($field + $fieldTranslations));
                
        $forms = $formsModels->getTreeForms(array('exclude_root' => true));

        $fieldForm->getElement('fid')->setMultiOptions($forms);
        
        if($this->getRequest()->isPost())
        {
            if($fieldForm->isValid($_POST))
            {         
                try
                {         
                    $formsFieldsModels->editField($fieldForm->getValues(), $id);
                    $fieldForm->addMessage('Pole formularza zostało zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $fieldForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $fieldForm->addMessage('Błąd podczas zapisu pola formularza.', Cms_Form::ERROR);
                }

            }
                        
            if($fieldForm->isErrors())
            {
                $fieldForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->form = $formsModels->getForm(array('fid' => $field['fid']));
        }
        
        $this->view->fieldForm   = $fieldForm;      
        $this->view->breadcrumbs = $formsModels->getBreadcrumbs($field['fid']);
    }
    
    public function deleteFieldsAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $formsFieldsModels = new Models_FormsFields;
            $fields = $this->_getParam('id');
            
            $toDelete = array();
            
            if(count($fields) > 0)
            {
                foreach($fields as $field)
                {
                    $toDelete[] = $field['value'];
                }
            }
            
            if(!empty($toDelete))
            {
                try     
                {
                    $formsFieldsModels->deleteFields($toDelete);
                    $result = array('message' => 'Wybrane pola formularza zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                }
                catch(Cms_Exception $e)
                {
                    $result = array('message' => 'Błąd podczas usuwania pól formularza.', 'type' => 'error');
                }

            }
                        
            echo json_encode($result);
        }
    }
}
?>