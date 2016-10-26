<?php

class Cms_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $acl = new Cms_Acl();
        $acl->addPermissions()
            ->addGroups()
            ->addUsers()
            ->addUserPermissions()
            ->addGroupPermissions();

        Zend_Registry::set('acl', $acl);
       
    }
}

?>
