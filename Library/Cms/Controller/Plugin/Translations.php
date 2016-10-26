<?php

class Cms_Controller_Plugin_Translations extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $session  = new Zend_Session_Namespace(CMS_DOMAIN.'.language');
        $settings = Zend_Registry::get('settings');
        $language = new Models_Languages;
                
        if($request->getParam('language') != null && $language->isAvaliable(strtolower($request->getParam('language'))))
        {
            $locale = new Zend_Locale(strtolower($request->getParam('language')));
        }
        elseif(isset($session->language) && !empty($session->language) && $language->isAvaliable(strtolower($session->language)))
        {
            $locale = new Zend_Locale(strtolower($session->language));
        }
        elseif($settings['general_language_method'])
        {
            $method = $settings['general_language_method'];

            if($method == 'auto')
            {
                $locale = new Zend_Locale('browser');
            }
            elseif($method == 'preferred')
            {
                $language = $language->getDefault();
                $locale = new Zend_Locale($language->code);
            }
        }
        else
        {
            $locale = new Zend_Locale('auto');
        }
        
        define('LOCALE_ID', $language->getIdFromCode($locale->getLanguage())->lid);        

        $session->language = $locale->getLanguage();
        Zend_Registry::set('Zend_Locale', $locale);
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $front     = Zend_Controller_Front::getInstance();
        $locale    = Zend_Registry::get('Zend_Locale');
        $language  = new Models_Languages;
        $translate = new Zend_Translate(
            array(
                'adapter' => 'Cms_Translate_Adapter_Db',
                'content' => 'dictonary'
            )
        );

        Zend_Registry::set('Zend_Translate', $translate);
        define('LOCALE_CODE', $locale->getLanguage());
    }
}

?>
