<?php

class Cms_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $errorPlugin = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $errorPlugin->setErrorHandlerModule($request->getModuleName());
    }

}

?>
