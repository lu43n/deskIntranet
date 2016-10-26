<?php

class Cms_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();

        $acl = Zend_Registry::get('acl');
        
        try
        {
            if($acl->has($request->getModuleName().'-'.$request->getControllerName().'-'.$request->getActionName()))
            {
                $resource = $request->getModuleName();
            }
            elseif($acl->has($request->getModuleName().'-'.$request->getControllerName()))
            {
                $resource = $request->getModuleName().'-'.$request->getControllerName();
            }
            else
            {
                $resource = $request->getModuleName();
            }
            
            if(!$acl->isUserAllowed($resource))
            {
                if($auth->getIdentity())
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
            $request->setControllerName('login');
        }
        catch(Cms_Acl_NoAccess_Exception $e)
        {
            $request->setControllerName('error');
            $request->setActionName('access');
        }
    }
}

?>
