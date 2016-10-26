<?php

class Admin_ChatController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-chat');
    }
    
    public function indexAction ()
    {
        $chatModels = new Models_Chat;
        
        $options = array(
            'group_by_recipient' =>   true,
            'sender'             =>   $this->identity->uid
        );

        $this->view->chats = $chatModels->getAllChats($options);
        
    }
    
    public function searchAction ()
    {
        $chatModels = new Models_Chat;
        
        $query = $this->_getParam('keyword', null);
        
        if($query)
        {
            $options = array(
                'group_by_recipient' =>   true,
                'search_keyword' =>   $query,
                'sender'             =>   $this->identity->uid
            );

            $this->view->keyword = $query;
            $this->view->chats = $chatModels->getAllChats($options);
        }
        else
        {
            $this->addFlashMessage('Brak słowa kluczowego', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'chat', 'action' => 'index'), 'admin', true);
        }
    }
    
    public function historyAction ()
    {
        $chatModels = new Models_Chat;
        $usersModels = new Models_Users;
        $recipient = $this->_getParam('id', null);
        
        if(!$recipient)
        {
            $this->addFlashMessage('Brak czatu o podanym identyfikatorze', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'chat', 'action' => 'index'), 'admin', true);
        }
        
        $options = array(
            'sender'      =>   $this->identity->uid,
            'recipient'   =>   $recipient,
            'limit'       =>   0
        );

        $this->view->chats = array_reverse($chatModels->getChatHistory($options));
        
        $options = array(
            'uid'   =>   $recipient
        );
        
        $this->view->user = $usersModels->getUser($options);
    }
    
//    public function deleteChatsAction ()
//    {    
//        $this->_helper->viewRenderer->setNoRender();
//        
//        if($this->getRequest()->isXmlHttpRequest())
//        {
//            $chatsModels = new Models_Chat;
//            $chats = $this->_getParam('id', null);
//            
//            $toDelete = array();
//            
//            if(count($chats) > 0)
//            {
//                foreach($chats as $chat)
//                {
//                    $toDelete[] = $chat['value'];
//                }
//            }
//            
//            if(!empty($toDelete))
//            {
//                try     
//                {
//                    $chatsModels->deleteChats($toDelete);
//                    $result = array('message' => 'Wybrane konwersacje zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
//                }
//                catch(Cms_Exception $e)
//                {
//                    $result = array('message' => 'Błąd podczas usuwania konwersacji.', 'type' => 'error');
//                }
//
//            }
//                        
//            echo json_encode($result);
//        }
//    }
    
    public function ajaxGetChatHistoryPartialAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $chatModels = new Models_Chat;
            
            $limit = $this->_getParam('limit', null);
            $id = $this->_getParam('id', null);

            if($limit && $id)
            {
                $options = array(
                    'sender'      =>   $this->identity->uid,
                    'recipient'   =>   $id,
                    'limit'       => $limit
                );

                $this->view->chats = array_reverse($chatModels->getChatHistory($options));
                $output = $this->view->render('chat/chat-history-partial.twig');

                $result = array('message' => 'Następne rekordy zostały pobrane.', 'type' => 'success', 'html' => $output);

                echo json_encode($result);
            }
        }
    }
    
    public function ajaxGetInitChatBoxesAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $users = $this->view->usersList;
            $chats = null;
            
            foreach($users as $user)
            {
                if(isset($_COOKIE['chat-' . $user['uid']]))
                {
                    $chats[] = $user['uid'];
                }
            }

            $result = array('message' => 'Czat został zainicjowany.', 'type' => 'success', 'chats' => $chats);
            
            echo json_encode($result);
        }
    }
    
    public function ajaxOpenChatAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $result = array('message' => 'Błąd podczas inicjowania czatu.', 'type' => 'error');
            
            $recipient = $this->_getParam('recipient', null);
            
            if($recipient != null)
            {
                $chatModels = new Models_Chat;
                $usersModels = new Models_Users;
                
                $recipient = $usersModels->getUser(array('uid' => $recipient));
                
                if($recipient)
                {
                    $this->view->recipient = $recipient;
                
                    $options = array(
                        'sender'     =>   $this->identity->uid,
                        'recipient'  =>   $recipient['uid']
                    );
                                        
                    $this->view->chats = $chatModels->getChatHistory($options);

                    $output = $this->view->render('chat/chat-box.twig');
                    $result = array('message' => 'Czat został rozpoczęty.', 'type' => 'success', 'html' => $output);
                }
            }
            
            echo json_encode($result);
        }
    }
    
    public function ajaxSetUserActivityAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $chatModels = new Models_Chat;
            $chatModels->setUserActivity($this->identity->uid);
            
            echo json_encode(array('message' => 'Aktywność została zapisana.' ,'type' => 'success'));
        }
    }
    
    public function ajaxRefreshUsersListAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $chatModels = new Models_Chat;
            
            $this->view->users = $chatModels->getUsersList($this->identity->uid);
            $output = $this->view->render('chat/users-list.twig');
            
            $result = array('message' => 'Lista użytkowników została zaktualizowana.', 'type' => 'success', 'html' => $output);

            echo json_encode($result);
        }
    }
    public function ajaxSendMessageAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if($this->acl->isUserAllowed('intranet-chat-create'))
            {
                $chatModels = new Models_Chat;

                $result = array('message' => 'Błąd podczas wysyłania wiadomości.', 'type' => 'error');

                $data = array(
                    'sender'    =>   $this->identity->uid,
                    'recipient' =>   $this->_getParam('recipient', null),
                    'message'   =>   $this->_getParam('message', null)
                );

                if(!empty($data['recipient']) && !empty($data['message']))
                {
                    $id = $chatModels->addMessage($data);

                    $options = array(
                        'cid'   =>   $id
                    );

                    $chatMessages = $chatModels->getChat($options);

                    $output = null;
                    if($chatMessages)
                    {
                        $this->view->chatMessage = $chatMessages;
                        $output = $this->view->render('chat/chat-message.twig');
                    }


                    $result = array('message' => 'Wiadomość zostałą wysłana.', 'type' => 'success', 'output' => $output);
                }
            }
            else
            {
                $result = array('message' => 'Brak uprawnień do wysyłania wiadomości.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
    
    public function ajaxRefreshChatsAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            $chatModels = new Models_Chat;
            
            $chatMessages = $chatModels->getNewChatMessages($this->identity->uid);
            
            if($chatMessages)
            {
                foreach($chatMessages as $k => $v)
                {
                    $view = $this->view;
                    $view->chatMessages = $chatMessages;
                    $chatMessages[$k]['output'] = $view->render('chat/chat-messages.twig'); 
                }
                
                $result = array('chats' => $chatMessages);
                echo json_encode($result);
            }
        }
    }
}

