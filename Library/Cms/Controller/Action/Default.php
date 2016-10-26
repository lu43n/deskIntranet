<?php

class Cms_Controller_Action_Default extends Cms_Controller_Action
{

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
}

?>
