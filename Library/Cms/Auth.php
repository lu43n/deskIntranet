<?php

class Cms_Auth implements Zend_Auth_Adapter_Interface
{
    protected $username, $password, $Models;
    
    public function __construct($username, $password, $Models) 
    {
        $config = Zend_Registry::get('config');
        
        $this->Models = $Models;
        $this->username = $username;
        $this->password = md5($password);
    }
    
    
    public function authenticate()
    {
        $usersModels = $this->Models;
        $user = $usersModels->authenticate($this->username, $this->password);

        if ((bool) $user == true) 
        {
            $usersModels->preAuthenticate($user);
            
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
        }

        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
    }
    
    public function saveIdentity ($identity)
    {
        $auth = Zend_Auth::getInstance();
        $storage = $auth->getStorage();
        $storage->write($identity);
    }
}

?>
