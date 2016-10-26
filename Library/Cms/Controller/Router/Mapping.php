<?php

class Cms_Controller_Router_Mapping extends Zend_Controller_Router_Route_Abstract
{
    /**
     * A method to publish the way the route operates.
     *
     * @see Zend/Controller/Router/Rewrite.php:392
     *
     * @return int
     */
    public function getVersion()
    {
        return 1;
    }

    /**
     * The required functions (required by Interface).
     *
     * @param Zend_Config $config The config object with defaults.
     *
     * @return void
     */
    public static function getInstance(Zend_Config $config)
    {
        return;
    }

    /**
     * Matches a user submitted path with a previously defined route.
     * Assigns and returns an array of defaults on a successful match.
     *
     * @param string $path Path used to match against this routing map
     *
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path)
    {       
        if(CMS_DOMAIN_ID == null)
        {
            return false;
        }

        return array('module' => 'Public', 'controller' => 'index');

    }

    /**
     * Assembles a URL path defined by this route
     *
     * @param array   $data    An array of variable and value pairs used as parameters
     * @param boolean $reset   Not used (required by interface)
     * @param boolean $encode  Not used (required by interface)
     * @param boolean $partial Not used (required by interface)
     *
     * @return string|false Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
    {
        return '';
    }
}


?>
