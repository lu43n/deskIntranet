<?php

class Admin_PmController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-pm');
    }
    
    public function indexAction ()
    {        
        $pmModel = new Models_PrivateMessages;
        
        $options = array(
            'sender_or_recipient'    => $this->identity->uid,
            'parent_id' => false,
            'orderby'   => 'pm.sent_at DESC'
        );
        
        $messages = $pmModel->getMessages($options);
        
        foreach($messages as $k => $message)
        {
            if($message['deleted_by_recipient'] == '1' && $message['recipient'] == $this->identity->uid)
            {
                unset($messages[$k]);
                continue;
            }
            
            if($message['deleted_by_sender'] == '1' && $message['sender'] == $this->identity->uid)
            {
                unset($messages[$k]);
                continue;
            }
            
            if($message['sender'] != $this->identity->uid)
            {
                if($message['recipient_received_at'] == null)
                {
                    $messages[$k]['unreaded'] = true;
                }
            }
            
            if($message['attachments'] != null)
            {
                $messages[$k]['has_attachments'] = true;
            }
                
            foreach($message['replies'] as $reply)
            {
                if($reply['sender'] != $this->identity->uid)
                {
                    if($reply['recipient_received_at'] == null)
                    {
                        $messages[$k]['unreaded'] = true;
                    }
                }
                
                if($reply['attachments'] != null)
                {
                    $messages[$k]['has_attachments'] = true;
                }
            }
        }
        
        $this->view->messages = $messages;
    }
    
    public function viewAction ()
    {
        $pmModel = new Models_PrivateMessages;
        
        $options = array(
            'sender_or_recipient' => $this->identity->uid,
            'pmid'      => $this->_getParam('id', null)
        );
        
        $message = $pmModel->getMessage($options);
                
        $canView = true;
        if($message['sender'] == $this->identity->uid && $message['deleted_by_sender'] == '1')
        {
            $canView = false;
        }
        elseif($message['recipient'] == $this->identity->uid && $message['deleted_by_recipient'] == '1')
        {
            $canView = false;
        }
        
        if(!$message || !$canView)
        {
            $this->addFlashMessage('Brak rekordu o podanym identyfikatorze', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'pm', 'action' => 'index'), 'admin', true);
        }
        
        $options = array(
            'parent_id'  =>  $message['pmid'],
            'orderby'   => 'pm.sent_at ASC'
        );
        
        $replies = $pmModel->getMessages($options);
        
        $this->view->message = $message;
        $this->view->replies = $replies;
        
        $pmModel->setReaded($message['pmid'], $this->identity->uid);
    }
    
    public function createAction () 
    {
        if(!$this->acl->isUserAllowed('intranet-pm-create'))
        {
            $this->accessError('Nie posiadasz uprawnień do tworzenia nowych wiadomości!');
        }
        
        $privateMessagesModel = new Models_PrivateMessages;
                
        $id = $this->_getParam('id', null);
        $recipient = $this->_getParam('recipient', null);
                
        $cmsForm = new Cms_Form('intranet-pm');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        if($recipient != null)
        {
            $cmsForm->getElement('recipient')->setValue($recipient);
        }
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['sender'] = $this->identity->uid;
                    
                    $privateMessagesModel->sendMessage($formData);
                    
                    $this->addFlashMessage('Wiadomość została wysłana', Cms_Form::SUCCESS);
                    $this->redirect(array('controller' => 'pm', 'action' => 'index'), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas wysyłania wiadomości.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        $this->view->cmsForm = $cmsForm;
    }
    

    public function replyAction () 
    {
        if(!$this->acl->isUserAllowed('intranet-pm-reply'))
        {
            $this->accessError('Nie posiadasz uprawnień do tworzenia odpowiedzi na wiadomości!');
        }
        
        $privateMessagesModel = new Models_PrivateMessages;
                
        $message = $this->_getParam('message', null);
        
        if($message != null)
        {
            $options = array(
                'pmid'       => $message,
                'sender_or_recipient' => $this->identity->uid,
            );
            
            $message = $this->view->message = $privateMessagesModel->getMessage($options);       
        }

        if(!$message)
        {
            $this->addFlashMessage('Brak rekordu o podanym identyfikatorze', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'pm', 'action' => 'index'), 'admin', true);
        }
        
        $options = array(
            'parent_id'  =>  $message['pmid'],
            'orderby'   => 'pm.sent_at ASC'
        );
        
        $this->view->replies = $privateMessagesModel->getMessages($options);
        
        $cmsForm = new Cms_Form('intranet-pm');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $cmsForm->removeElement('subject');
        $cmsForm->removeElement('recipient');

        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['sender'] = $this->identity->uid;
                    $formData['parent_id'] = $message['pmid'];
                    $formData['subject'] = $message['subject'];
                    $formData['recipient'] = $message['sender'];
                    
                    $privateMessagesModel->sendMessage($formData);
                    
                    $this->addFlashMessage('Wiadomość została wysłana', Cms_Form::SUCCESS);
                    $this->redirect(array('controller' => 'pm', 'action' => 'index'), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas wysyłania wiadomości.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        $this->view->cmsForm = $cmsForm;
    }
    
    public function deleteAction () 
    {
        if(!$this->acl->isUserAllowed('intranet-pm-remove'))
        {
            $this->accessError('Nie posiadasz uprawnień do usuwania wiadomości!');
        }
        
       $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $pmModels = new Models_PrivateMessages;
            $messages = $this->_getParam('id', null);

            $toDelete = array();

            if(count($messages) > 0)
            {
                foreach($messages as $message)
                {
                    $toDelete[] = $message['value'];
                }
            }

            if($toDelete)
            {
                try     
                {
                    $pmModels->deleteMessages($toDelete, $this->identity->uid);                         
                    $result = array('message' => 'Wiadomości zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                }
                catch(Cms_Exception $e)
                {
                    $result = array('message' => 'Błąd podczas usuwania wiadomości.', 'type' => 'error');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania wiadomości.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
    
    public function getAutocompleteUsersAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        $pmModel = new Models_PrivateMessages;
        
        $options = array(
            'term' => $this->_getParam('term', null),
            'id'      => $this->_getParam('id', null),
            'uid'   => $this->identity->uid
        );
        
        echo $pmModel->getAutocompleteUsers($options);
    }
}

