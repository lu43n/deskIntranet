<?php
/**
 * deskCMS
 * 
 * @copyright Copyright (c) 2012
 * @version 1.0
 * @author deskCMS Team
 * @see http://deskcms.pl/
 * 
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{   
    protected function _initLogger ()
    {
        $this->bootstrap('log');
        if (!$this->hasResource('log')) 
        {
            return false;
        }
        
        $logger = $this->getResource('log');
        $logger->setTimestampFormat('d-m-Y H:i:s');
    
        Zend_Registry::set('logger', $logger);
    }
    
    /**
     * Pobieramy konfirugrację strony
     * 
     * @return void
     */
    protected function _initConfig ()
    {
        $this->bootstrap('db');
        
        $settingsModels = new Models_Settings();
        $settings = array();

        if($settingsModels->getSettings()->count() > 0)
        {
            foreach($settingsModels->getSettings() as $setting)
            {       
                $settings[strtolower($setting->name)] = $setting['value'];
            }

            Zend_Registry::set('settings', $settings);
        }
        
        $settings = new Zend_Config_Ini(APPLICATION_PATH . DS . 'Configs' . DS . 'Application.ini', 'installation');
        Zend_Registry::set('config', $settings->toArray());
    }
    
    /**
     * Inicjujemy router, wykrywamy domenę, ustawiamy odpowiednie ścieżki
     * 
     * @return void
     */

    protected function _initRouter ()
    {
        $this->bootstrap(array('frontController'));
        $frontController = $this->getResource('frontController');

        $ssl = false;
        $domainName = str_replace('www.', '', $_SERVER['HTTP_HOST']);

        $domainsModels = new Models_Domains;
        $domain = $domainsModels->getDomain($domainName, $ssl);

        $router = $frontController->getRouter();

        if($domain)
        {
            define('CMS_DOMAIN', $domain->title);
            define('CMS_DOMAIN_ID', $domain->did);

            $router->addRoute('default', new Cms_Controller_Router_Mapping());            
            $router->addRoute('admin', new Zend_Controller_Router_Route(':controller/:action/*', array('module' => 'Admin', 'controller' => 'index', 'action' => 'index'))); 
        }
        
        $router->removeDefaultRoutes();
    }
    
    /**
     * Inicjacja wymagań systemu, w razie nie powodzenia zatrzymujemy system
     * 
     * @return void
     */

    protected function _initRequirements ()
    {
        $this->bootstrap('config');

        $config = Zend_Registry::get('config');

        if (version_compare(phpversion(), '5.2.0', '<') === true) 
        {
            die('ERROR: Your PHP version is ' . phpversion() . '. deskCMS requires PHP 5.2.0 or newer.');
        }

        $licenseKey = base64_decode($config['client']['product_key']);
        $siteDomain = $_SERVER['HTTP_HOST'];

        $result = "";	

        for ($i = 0; $i < strlen($licenseKey); $i++) 
        {
                $byte_in = ord($licenseKey[$i]); 

                $byte_out = ($byte_in - 4);

                $result .= chr($byte_out);
        }
        
        if(strpos($result, $siteDomain) === false)
        {
                //die('Twoja licencja jest niepoprawna! Skontaktuj siÄ™ z administratorem!');
        }
    }
    
    /**
     * Inicjacja pluginów kontrolera
     * 
     * @return void
     */

    protected function _initPlugins ()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        // Inicjujemy zestaw uprawnień
        $frontController->registerPlugin(new Cms_Controller_Plugin_Acl);
        // Inicjujemy system szablonów
        $frontController->registerPlugin(new Cms_Controller_Plugin_View);
        // Inicjujemy wielojęzyczność systemu, translacje tekstów
        $frontController->registerPlugin(new Cms_Controller_Plugin_Translations);
        // Inicjujemy obsługę błędów
        $frontController->registerPlugin(new Cms_Controller_Plugin_ErrorHandler);
    }
    
    /** 
     * Inicjacja debugera
     * 
     * @return void
     */
    
    protected function _initZFDebug()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $this->bootstrap(array('db','frontController', 'cachemanager'));
        
        $db = $this->getPluginResource('db')->getDbAdapter();
        $cache = $this->getPluginResource('cachemanager')->getCacheManager()->getCache("database");
        
        $options = array(
            'plugins' => array('Variables', 
                               'Memory', 
                               'Time', 
                               'Registry', 
                               'Exception',
                               'Database' => array('adapter' => array('standard' => $db)),
                               'Cache' => array('backend' => $cache->getBackend()))
        );

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $frontController = $this->getResource('frontController');
        
        // Jeżeli pracujemy w środowisku deweloperskim, włączamy konsolę
        if($this->getEnvironment() == 'development')
        {
            //$frontController->registerPlugin($debug);        
        }
    }
}

