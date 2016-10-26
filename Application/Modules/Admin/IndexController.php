<?php

class Admin_IndexController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-dashboard');
    }
    
    public function indexAction()
    {
        $newsModels = new Models_News;
        
        $this->view->featured_newses = $newsModels->getNewses(array('limit' => 5, 'type' => 1, 'order' => 'created_at DESC'));
        $this->view->newses = $newsModels->getNewses(array('limit' => 5, 'type' => 0, 'order' => 'created_at DESC'));
        
        $eventsModels = new Models_Events;
        
        $this->view->featured_events = $eventsModels->getEvents(array('limit' => 5, 'type' => 1, 'date' => 'comming'));
        $this->view->events = $eventsModels->getEvents(array('limit' => 5, 'type' => 0, 'date' => 'comming'));
        
        $usersModels = new Models_Users;
        
        $this->view->users = $usersModels->getUsers(array('order' => 'last_login DESC', 'limit' => '10'));
        
        $documentsModels = new Models_Documents;
        
        $this->view->documents = $documentsModels->getDocuments(array('order' => 'modified_at DESC', 'limit' => 10, 'type' => 'DOC'));
    }

    public function keepaliveAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        $this->session->resetSingleInstance();
        
        echo 'ok';
    }

}

