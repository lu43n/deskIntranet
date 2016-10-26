<?php

class Admin_UsersController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        if(!$this->acl->isUserAllowed('intranet-groups') && !$this->acl->isUserAllowed('intranet-users'))
        {
            $this->accessError('Nie posiadasz uprawnień do modułu użytkownicy!');
        }
    }
    
    public function indexAction ()
    {        
        $usersModels = new Models_Users;
        $groupsModels = new Models_Groups;
        $id = $this->_getParam('id', null);
        
        $options = array();
        if($id)
        {
            $this->view->group       = $groupsModels->getGroup(array('gid' => $id));
            $this->view->breadcrumbs = $groupsModels->getBreadcrumbs($id);
            $this->view->users = $usersModels->getUsers(array('gid' => $id));
            
            $options['parent_id'] = $id;
        }
        else
        {
            $options['depth'] = 1;
        }

        $this->view->groups = $groupsModels->getGroups($options);
    }
    
    public function searchAction ()
    {
        $groupsModels = new Models_Groups;
        $usersModels  = new Models_Users;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);

        try
        {
            $cmsSearchGroups = new Cms_Search;
            $cmsSearchGroups->setIndex(Cms_Search_Index::getIndex('Groups'));
            $resultsGroups = $cmsSearchGroups->find($keyword);

            if($resultsGroups)
            {
                $this->view->groups = $groupsModels->getGroups(array('gid' => $resultsGroups));
            }

            $cmsSearchUsers = new Cms_Search;
            $cmsSearchUsers->setIndex(Cms_Search_Index::getIndex('Users'));
            $resultsUsers = $cmsSearchUsers->find($keyword);

            if($resultsUsers)
            {
                $this->view->users = $usersModels->getUsers(array('uid' => $resultsUsers));
            }
        }
        catch(Exception $e) { }
    }
    
    public function addGroupAction ()
    {
        
        $groupsModels      = new Models_Groups;
        $permissionsModels = new Models_Permissions;
        
        $id = $this->_getParam('id', null);        
               
        if(!$this->acl->isUserAllowed('intranet-groups-create'))
        {
            $this->accessError('Nie posiadasz uprawnień do dodawania elementów w tej grupie!');
        }
        
        $groupForm = new Cms_Form('intranet-groups-general');    
        $groupForm->disableForm(true);
                
        $permissionsForm = new Cms_Form('intranet-groups-permissions');    
        $permissionsForm->disableForm(true);
        
        $form = new Cms_Form_SubForm('group');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post')
             ->addSubForms(array($groupForm, $permissionsForm));
        
        $groups = $groupsModels->getGroupsTree();
        $groupForm->getElement('parent_id')->setMultiOptions($groups)->setValue($id);

        $permissions = $permissionsModels->getPermissionsTree(array('exclude_root' => true));
        $permissionsForm->getElement('permissions')->setMultiOptions($permissions);

        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($groupForm->getValues() + $permissionsForm->getValues());
                    
                    $groupsModels->addGroup($formData);
                    $groupForm->addMessage('Grupa została dodana.', Cms_Form::SUCCESS);             
                }
                catch (Cms_Form_Exception $e)
                {
                    $groupForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $groupForm->addMessage('Błąd podczas dodawania grupy.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->group       = $groupsModels->getGroup(array('gid' => $id));
            $this->view->breadcrumbs = $groupsModels->getBreadcrumbs($id);
        }
        
        $this->view->groupForm       = $groupForm;
        $this->view->permissionsForm = $permissionsForm;  
    }

    public function editGroupAction ()
    {
        $groupsModels            = new Models_Groups;
        $permissionsModels       = new Models_Permissions;
        $groupsTranslationModels = new Models_GroupsTranslation;
        
        $id = $this->_getParam('id', null);
        $group = $groupsModels->getGroup(array('gid' => $id));
        
        if(!$group)
        {
            $this->addFlashMessage('Grupa nie została znaleziona!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'users'), 'admin', true);
        }  
        
        if(!$this->acl->isUserAllowed('intranet-groups-edit'))
        {
            $this->accessError('Nie posiadasz uprawnień do edycji grup!');
            return;
        }
        
        $groupForm = new Cms_Form('intranet-groups-general');    
        $groupForm->disableForm(true);
                
        $permissionsForm = new Cms_Form('intranet-groups-permissions');    
        $permissionsForm->disableForm(true);
        
        $form = new Cms_Form_SubForm('group');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post')
             ->addSubForms(array($groupForm, $permissionsForm));

        $groupTranslations = $groupsTranslationModels->getTranslations($id);
        
        $form->populate($group + $groupTranslations);

        $groups = $groupsModels->getGroupsTree(array('exclude_childrens' => array('path' => $group['path'])));
        $groupForm->getElement('parent_id')->setMultiOptions($groups);

        $permissions = $permissionsModels->getPermissionsTree(array('exclude_root' => true));
        $permissionsForm->getElement('permissions')->setMultiOptions($permissions);

        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($groupForm->getValues() + $permissionsForm->getValues());
                    
                    $groupsModels->editGroup($formData, $id);
                    $groupForm->addMessage('Grupa została zapisana.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $groupForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $groupForm->addMessage('Błąd podczas edycji grupy.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->group       = $group;
            $this->view->breadcrumbs = $groupsModels->getBreadcrumbs($id);
        }
        
        $this->view->groupForm       = $groupForm;
        $this->view->permissionsForm = $permissionsForm;  
    }   

    public function deleteGroupsAction ()
    {            
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if(!$this->acl->isUserAllowed('intranet-groups-remove'))
            {
                echo json_encode(array('message' => 'Nie posiadasz uprawnień do usuwania grup.', 'type' => 'error'));
                return;
            }
            
            $groupsModels = new Models_Groups;
            $groups = $this->_getParam('id', null);

            $toDelete = array();
            $notAllowed = 0;
            
            if(count($groups) > 0)
            {
                foreach($groups as $group)
                {
                    $toDelete[] = $group['value'];
                }
            }

            if($toDelete)
            {
                try     
                {
                    $groupsModels->deleteGroups($toDelete);                         
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
    
    public function addUserAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-users-create'))
        {
            $this->accessError('Nie posiadasz uprawnień do tworzenia użytkowników!');
            return;
        }
        
        $usersModels       = new Models_Users;
        $groupsModels      = new Models_Groups;
        $permissionsModels = new Models_Permissions;
                
        $id = $this->_getParam('id', null);

        $userForm = new Cms_Form('intranet-users-general');    
        $userForm->disableForm(true);
                
        $userdataForm = new Cms_Form('intranet-users-userdata');    
        $userdataForm->disableForm(true);
        
        $permissionsForm = new Cms_Form('intranet-users-permissions');    
        $permissionsForm->disableForm(true);
        
        $form = new Cms_Form_SubForm('user');
        $form->addSubForms(array($userForm, $userdataForm, $permissionsForm))
             ->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post');
        
        $groups = $groupsModels->getGroupsTree(array('exclude_root' => true));
        $userForm->getElement('gid')->setMultiOptions($groups);

        $permissions = $permissionsModels->getPermissionsTree(array('exclude_root' => true));
        $permissionsForm->getElement('permissions')->setMultiOptions($permissions);

        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($userForm->getValues() + $userdataForm->getValues() + $permissionsForm->getValues());
                    
                    $usersModels->addUser($formData);
                    $userForm->addMessage('Użytkownik został dodany.', Cms_Form::SUCCESS);
                    $userdataForm->addMessage('Użytkownik został dodany.', Cms_Form::SUCCESS);
                }
                catch (Cms_Exception $e)
                {
                    $userForm->addMessage('Błąd podczas dodawania użytkownika.', Cms_Form::SUCCESS);
                    $userdataForm->addMessage('Błąd podczas dodawania użytkownika.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->group       = $groupsModels->getGroup($id);
            $this->view->breadcrumbs = $groupsModels->getBreadcrumbs($id);
        }
        
        $this->view->userForm        = $userForm;
        $this->view->userdataForm    = $userdataForm; 
        $this->view->permissionsForm = $permissionsForm;      
    }
    
    public function editUserAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-users-edit'))
        {
            $this->accessError('Nie posiadasz uprawnień do edycji użytkowników!');
            return;
        }
        
        $usersModels       = new Models_Users;
        $groupsModels      = new Models_Groups;
        $permissionsModels = new Models_Permissions;
                
        $id = $this->_getParam('id', null);
                
        $user = $usersModels->getUser(array('uid' => $id));
        
        if(!$user)
        {
            $this->addFlashMessage('Użytkownik nie został‚ znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'users'), 'admin', true);
        }         
        
        $userdataForm = new Cms_Form('intranet-users-userdata');    
        $userdataForm->disableForm(true);
        
        $permissionsForm = new Cms_Form('intranet-users-permissions');    
        $permissionsForm->disableForm(true);
        
        $userForm = new Cms_Form('intranet-users-general');    
        $userForm->disableForm(true);
         
        $form = new Cms_Form_SubForm('user');
        $form->setAction($this->getRequest()->getRequestUri())
             ->setMethod('post')
             ->addSubForms(array($userForm, $userdataForm, $permissionsForm));

        $form->populate($user);
        
        $groups = $groupsModels->getGroupsTree(array('exclude_root' => true));
        $userForm->getElement('gid')->setMultiOptions($groups);

        $permissions = $permissionsModels->getPermissionsTree(array('exclude_root' => true));
        $permissionsForm->getElement('permissions')->setMultiOptions($permissions);
        
        $userForm->getElement('password')->clearValidators()->setRequired(false);
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {                           
                try
                {                    
                    $formData = ($userForm->getValues() + $userdataForm->getValues() + $permissionsForm->getValues());
                    
                    $usersModels->editUser($formData, $user['uid']);
                    $userForm->addMessage('Użytkownik został zapisany.', Cms_Form::SUCCESS);
                    $userdataForm->addMessage('Użytkownik został zapisany.', Cms_Form::SUCCESS);
                }
                catch (Cms_Exception $e)
                {
                    $userForm->addMessage('Błąd podczas zapisu użytkownika.', Cms_Form::SUCCESS);
                    $userdataForm->addMessage('Błąd podczas zapisu użytkownika.', Cms_Form::SUCCESS);
                }
            }

            if($form->isErrors())
            {
                $form->populate($_POST);
            }
        }

        $this->view->user            = $usersModels->getUser(array('uid' => $id));
        $this->view->userForm        = $userForm;
        $this->view->userdataForm    = $userdataForm; 
        $this->view->permissionsForm = $permissionsForm;        
    }
    
    public function deleteUsersAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if(!$this->acl->isUserAllowed('intranet-users-remove'))
            {
                $result = array('message' => 'Nie posiadasz uprawnień do usuwania użytkowników!', 'type' => 'error');
                echo json_encode($result);
                return;
            }
            
            $usersModels = new Models_Users;
            $users = $this->_getParam('id', null);

            $toDelete = array();

            if(count($users) > 0)
            {
                foreach($users as $user)
                {
                    $toDelete[] = $user['value'];
                }
            }

            if($toDelete)
            {
                try     
                {
                    $usersModels->deleteUsers($toDelete);                         
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

