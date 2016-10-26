<?php

class Admin_EmployeesController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-employees');
    }
    
    public function indexAction ()
    {
        $employeesModels = new Models_Employees;
        
        $this->view->employees = $employeesModels->getEmployees();
    }
    
    public function viewAction ()
    {
        $usersModels = new Models_Users;
        
        $id = $keyword = $this->_getParam('id', null);

        $user = $usersModels->getUser(array('uid' => $id));
        
        if(!$user || $id == null)
        {
            $this->addFlashMessage('Użytkownik nie został‚ znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'employees'), 'admin', true);
        }         
              
        $this->view->user = $user;
    }


    public function searchAction ()
    {
        $usersModels = new Models_Users;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try 
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Users'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->users = $usersModels->getUsers(array('uid' => $results));
            }
        }
        catch(Exception $e) { }
    }
}

