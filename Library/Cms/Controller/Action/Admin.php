<?php

class Cms_Controller_Action_Admin extends Cms_Controller_Action
{
    public function init ()
    {
        parent::init();
        
        $ModelsUsers = new Models_Chat;
        
        $ModelsPm = new Models_PrivateMessages;

        if($this->identity)
        {
            $ModelsUsers->setUserActivity($this->identity->uid);
            $this->view->usersList = $ModelsUsers->getUsersList($this->identity->uid);
            
            $options = array(
                'recipient'      => $this->identity->uid,
                'recipient_received_at' => false,
                'deleted_by_recipient'  => 0
            );         
                        
            $newPmMessages = $ModelsPm->getMessages($options);
            
            if($newPmMessages)
            {

                
                $this->view->newPmMessages = count($newPmMessages);
            }
        }     
    }
    
    public function checkAccess($resource) 
    {
        try 
        {
            if(!$this->acl->isUserAllowed($resource))
            {
                if($this->identity)
                {
                    throw new Cms_Acl_NoAccess_Exception('Access is denied for this page area. Please contact with administrator.', 403);
                }
                else
                {
                    throw new Cms_Acl_Exception('Access is denied for this page area. Please contact with administrator.', 403);
                }
            }
        }
        catch(Cms_Acl_Exception $e)
        {
            $this->getResponse()->setHttpResponseCode(403);
            $this->_forward('index', 'login');
        }
        catch(Cms_Acl_NoAccess_Exception $e)
        {
            $this->_forward('access', 'error');
        }
    }
    
    public function isAllowed ($resource)
    {
        if(!$this->acl->isUserAllowed($resource))
        {
            if($this->identity)
            {
                return false;
            }
        }
        
        return true;
    }
    
    public function accessError($message) 
    {
        if($this->identity)
        {
            $this->_forward('access', 'error', null, array('message' => $message));
        }
        else
        {
            $this->getResponse()->setHttpResponseCode(403);
            $this->_forward('index', 'login');
        }
    }

}

?>
