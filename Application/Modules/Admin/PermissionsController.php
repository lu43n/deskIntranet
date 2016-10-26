<?php

class Admin_PermissionsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-permissions');
    }
    
    public function indexAction ()
    {
        $permissionsModels = new Models_Permissions;
        $id = $this->_getParam('id', null);
        $options = array('parent_id' => $id);

        if($id)
        {
            $this->view->permission = $permissionsModels->getPermission(array('pid' => $id));
        }
        else
        {
            $options['depth'] = 1;
        }
        
        $this->view->permissions = $permissionsModels->getPermissions($options);        
        $this->view->breadcrumbs = $permissionsModels->getBreadcrumbs($id);
    }

    public function searchAction ()
    {
        $permissionsModels = new Models_Permissions;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Permissions'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->permissions = $permissionsModels->getPermissions(array('pid' => $results));
            }
        }
        catch(Exception $e) { }
    }
    
    public function addAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-permissions-create'))
        {
            $this->accessError('Nie posiadasz uprawnień do tworzenia nowych elementów!');
        }
        
        $permissionsModels = new Models_Permissions;
        $groupsModels      = new Models_Groups;
        $usersModels       = new Models_Users;
        
        $id = $this->_getParam('id', null);
        
        $permissionsForm = new Cms_Form('intranet-permissions-general');    
        $permissionsForm->disableForm(true);
        
        $permissionsGroupsForm = new Cms_Form('intranet-permissions-groups');    
        $permissionsGroupsForm->disableForm(true);
        
        $permissionsUsersForm = new Cms_Form('intranet-permissions-users');    
        $permissionsUsersForm->disableForm(true);
 
        $form = new Cms_Form_SubForm('permissions');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post')
             ->addSubForms(array($permissionsForm, $permissionsGroupsForm, $permissionsUsersForm));
                
        $permissions = $permissionsModels->getPermissionsTree();
        $permissionsForm->getElement('parent_id')->setMultiOptions($permissions)->setValue($id);
      
        $groups = $groupsModels->getGroupsTree(array('exclude_root' => true));
        $permissionsGroupsForm->getElement('groups')->setMultiOptions($groups);
        
        $users = $usersModels->getUsersForPermissions();
        $permissionsUsersForm->getElement('users')->setMultiOptions(array($users));
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($permissionsForm->getValues() + $permissionsGroupsForm->getValues() + $permissionsUsersForm->getValues());
                    
                    $permissionsModels->addPermission($formData);
                    $permissionsForm->addMessage('Uprawnienie zostało dodane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $permissionsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $permissionsForm->addMessage('Błąd podczas dodawania uprawnienia.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->permission  = $permissionsModels->getPermission(array('pid' => $id));
            $this->view->breadcrumbs = $permissionsModels->getBreadcrumbs($id);
        }
                
        $this->view->permissionsForm       = $permissionsForm;
        $this->view->permissionsGroupsForm = $permissionsGroupsForm;
        $this->view->permissionsUsersForm  = $permissionsUsersForm;
    }

    public function editAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-permissions-edit'))
        {
            $this->accessError('Nie posiadasz uprawnień do edycji elementów!');
        }
        
        $permissionsModels            = new Models_Permissions;
        $permissionsTranslationModels = new Models_PermissionsTranslation;
        $usersModels                  = new Models_Users;
        $groupsModels                 = new Models_Groups;
        
        $id = $this->_getParam('id', null);
        $permission = $permissionsModels->getPermission(array('pid' => $id));

        if(!$permission)
        {
            $this->addFlashMessage('Uprawnienie nie zostało znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'permissions'), 'admin', true);
        } 
        
        $permissionsForm = new Cms_Form('intranet-permissions-general');    
        $permissionsForm->disableForm(true);
        
        $permissionsGroupsForm = new Cms_Form('intranet-permissions-groups');    
        $permissionsGroupsForm->disableForm(true);
                
        $permissionsUsersForm = new Cms_Form('intranet-permissions-users');    
        $permissionsUsersForm->disableForm(true);
        
        $form = new Cms_Form_SubForm('permissions');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post')
             ->addSubForms(array($permissionsForm, $permissionsGroupsForm, $permissionsUsersForm));

        $permissionTranslations = $permissionsTranslationModels->getTranslations(array('pid' => $id));

        $form->populate($permission + $permissionTranslations);

        $permissions = $permissionsModels->getPermissionsTree(array('exclude_childrens' => array('path' => $permission['path'])));
        $permissionsForm->getElement('parent_id')->setMultiOptions($permissions);
        
        $groups = $groupsModels->getGroupsTree(array('exclude_root' => true));
        $permissionsGroupsForm->getElement('groups')->setMultiOptions($groups);

        $users = $usersModels->getUsersForPermissions();
        $permissionsUsersForm->getElement('users')->setMultiOptions(array($users));
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($permissionsForm->getValues() + $permissionsGroupsForm->getValues() + $permissionsUsersForm->getValues());
                    $formData['slug']  = $permission['slug'];
                    $formData['path']  = $permission['path'];
                    $formData['depth'] = $permission['depth'];
                    
                    $permissionsModels->editPermission($formData, $id);
                    $permissionsForm->addMessage('Uprawnienie zostało zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $permissionsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $form->addMessage('Błąd podczas edycji uprawnienia.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->permission  = $permission;
            $this->view->breadcrumbs = $permissionsModels->getBreadcrumbs($id);
        }
        
        $this->view->permissionsForm       = $permissionsForm;
        $this->view->permissionsGroupsForm = $permissionsGroupsForm;
        $this->view->permissionsUsersForm  = $permissionsUsersForm; 
    }   
    
    public function deletePermissionsAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if(!$this->acl->isUserAllowed('intranet-permissions-remove'))
            {
                $result = array('message' => 'Nie posiadasz uprawnień do usuwania elementów!', 'type' => 'error');
                echo json_encode($result);
                return;
            }
            $permissionsModels = new Models_Permissions;
            $permissions = $this->_getParam('id', null);

            $toDelete = array();

            if(count($permissions) > 0)
            {
                foreach($permissions as $permission)
                {
                    $toDelete[] = $permission['value'];
                }
            }

            if($toDelete)
            {
                try     
                {
                    $permissionsModels->deletePermissions($toDelete);                         
                    $result = array('message' => 'Rekordy zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                }
                catch(Cms_Exception $e)
                {
                    $result = array('message' => 'Błąd podczas usuwania.', 'type' => 'error');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
}

