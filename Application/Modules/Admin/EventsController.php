<?php

class Admin_EventsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-events');
    }
    
    public function indexAction ()
    {
        $eventsModels = new Models_Events;
        $this->view->comming_events = $eventsModels->getEvents(array('date' => 'comming'));        
        $this->view->past_events = $eventsModels->getEvents(array('date' => 'past'));        
    }
    
    public function viewAction ()
    {
        $eventsModels = new Models_Events;
        
        $id = $this->_getParam('id', null);
        $event = $eventsModels->getEvent(array('eid' => $id));

        if(!$event)
        {
            $this->addFlashMessage('Wydarzenie nie zostało znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'news'), 'admin', true);
        } 
        
        $this->view->event = $event;
    }
    
    public function searchAction ()
    {
        $eventsModels = new Models_Events;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Events'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->events = $eventsModels->getEvents(array('eid' => $results));
            }
        }
        catch(Exception $e) {}
    }
    
    public function addAction ()
    {
        $this->checkAccess('intranet-events-create');
        
        $eventsModels = new Models_Events;
        
        $cmsForm = new Cms_Form('intranet-events');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $eventsModels->addEvent($formData);
                    $cmsForm->addMessage('Wydarzenie zostało dodane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania wydarzenia.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }

        $this->view->cmsForm = $cmsForm;
    }

    public function editAction ()
    {
        $eventsModels = new Models_Events;
        $eventsTranslationModels = new Models_EventsTranslation;
        
        $id = $this->_getParam('id', null);
        $event = $eventsModels->getEvent(array('eid' => $id));

        if(!$event)
        {
            $this->addFlashMessage('Wydarzenie nie zostało znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'events'), 'admin', true);
        } 
        
        if($this->isAllowed('intranet-events-edit') == false)
        {
            if($this->isAllowed('intranet-events-editown') == true)
            {
                if($this->identity->uid != $event['uid'])
                {
                    $this->accessError('Brak uprawnień do edycji tego wydarzenia!');
                }
            }
            else
            {
                $this->accessError('Brak uprawnień do edycji tego wydarzenia!');
            }
        }
        
        $cmsForm = new Cms_Form('intranet-events');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');

        $eventsTranslations = $eventsTranslationModels->getEventsTranslations(array('eid' => $id));

        $cmsForm->populate($event + $eventsTranslations);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    
                    $eventsModels->editEvent($formData, $id);
                    $cmsForm->addMessage('Wydarzenie zostało zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas edycji wydarzenia.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }

        if($event)
        {
            $this->view->event  = $event;
        }
        
        $this->view->cmsForm = $cmsForm;
    }   
    
    public function deleteEventsAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if($this->isAllowed('intranet-events-delete') == false && $this->isAllowed('intranet-events-editown') == false)
            {
                $this->accessError('Brak uprawnień do usuwania wydarzeń!');
            }
            
            $eventsModels = new Models_Events;
            $events = $this->_getParam('id', null);

            $toDelete = array();

            if(count($events) > 0)
            {
                foreach($events as $event)
                {
                    $toDelete[] = $event['value'];
                }
            }

            if($toDelete)
            {
                $accessErrors = false;
                
                $events = $eventsModels->getEvents(array('eid' => $toDelete));
                
                foreach($events as $event)
                {
                    if($this->identity->uid != $event['uid'])
                    {
                        $accessErrors = true;
                    }
                }
                
                if($accessErrors == false)
                {
                    try     
                    {
                        $eventsModels->deleteEvents($toDelete);                         
                        $result = array('message' => 'Wydarzenia zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                    }
                    catch(Cms_Exception $e)
                    {
                        $result = array('message' => 'Błąd podczas usuwania wydarzeń.', 'type' => 'error');
                    }
                }
                else
                {
                    $result = array('message' => 'Brak uprawnień do usunięcia wybranych wydarzeń!', 'type' => 'alert');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania wydarzeń.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
}

