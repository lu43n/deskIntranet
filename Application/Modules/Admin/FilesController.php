<?php

include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderConnector.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinder.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderVolumeDriver.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderVolumeLocalFileSystem.class.php';

class Admin_FilesController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        if(!$this->acl->isUserAllowed('intranet-files'))
        {
            $this->accessError('Nie posiadasz uprawnień do dodawania definicji!', 'ajax');
        }
        
        $uploadsDir = SYSTEM_PATH.DS.'Uploads';
        
        if(!file_exists($uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt)) || !is_dir($uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt)))
        {
            mkdir($uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt));
        }
        
        $this->options = array(
                'roots' => array(
                    array(
                            'alias'         => 'Katalog publiczny',
                            'driver'        => 'LocalFileSystem',
                            'path'          => $uploadsDir.DS.'Public',
                            'accessControl' => array($this, 'access')
                    )
                )
        );
        
        if(file_exists($uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt)) && is_dir($uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt)))
        {
            $this->options['roots'][] = array(
                'alias'         => 'Katalog prywatny',
                'driver'        => 'LocalFileSystem',
                'path'          => $uploadsDir.DS.'Private'.DS.md5($this->identity->uid.$this->identity->salt),
                'accessControl' => array($this, 'access')
            );
        }
    }
    
    public function access($attr, $path, $data, $volume) 
    {
        return strpos(basename($path), '.') === 0 ? !($attr == 'read' || $attr == 'write') : null;
    }
    
    public function connectorAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        
        $connector = new elFinderConnector(new elFinder($this->options));
        $connector->run();
    }
    
    public function fileManagerAction ()
    {
        $this->view->type = $this->_getParam('type', null);
        $this->view->target = $this->_getParam('target', null);
        $this->view->dialog_id = $this->_getParam('dialog_id', null);
        $this->view->mime = $this->_getParam('mime', null);
    }
}

