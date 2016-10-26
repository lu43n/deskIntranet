<?php

class Cms_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {                  
        $settings = Zend_Registry::get('settings');

        if($request->getModuleName() == 'Admin')
        {
            $theme = (isset($settings['general_admin_theme']) && !empty($settings['general_admin_theme']) ? $settings['general_admin_theme'] : 'Admin');
        }
        else
        {
            $theme = (isset($settings['general_site_theme']) && !empty($settings['general_site_theme']) ? $settings['general_site_theme'] : 'Public');            
        }
        
        $view = new Zwig_View();

        $loader = new Twig_Loader_Filesystem(array());
        $zwig = new Zwig_Environment($view, $loader, array(
            'cache' => SYSTEM_PATH . DS . 'Cache' . DS . 'Templates',
            'auto_reload' => true,
            'debug' => true
        ));
        
        $zwig->addExtension(new Twig_Extension_Debug());

        $view->setEngine($zwig);
        $view->setScriptPath(SYSTEM_PATH . DS . 'Templates' . DS . $theme);
        $view->setFilterPath(SYSTEM_PATH . DS . 'Templates' . DS . $theme);
        $view->setHelperPath(SYSTEM_PATH . DS . 'Templates' . DS . $theme);

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, array(
            'viewSuffix' => 'twig',
        ));

        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }
}

?>
