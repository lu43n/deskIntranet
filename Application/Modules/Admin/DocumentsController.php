<?php

include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderConnector.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinder.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderVolumeDriver.class.php';
include_once SYSTEM_PATH.DS.'Library'.DS.'Cms'.DS.'elFinder'.DS.'elFinderVolumeLocalFileSystem.class.php';

class Admin_DocumentsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-documents');
    }

    public function indexAction ()
    {
        $documentsModels = new Models_Documents;
        $id = $this->_getParam('id', null);
        $options = array('parent_id' => $id);

        if($id && $id != 0)
        {
            $this->view->document = $documentsModels->getDocument(array('did' => $id));
        }
        else
        {
            $options['depth'] = 1;
        }
        
        $this->view->documents = $documentsModels->getDocuments($options);        
        $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($id);
    }
    
    public function searchAction ()
    {
        $documentsModels = new Models_Documents;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Documents'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->documents = $documentsModels->getDocuments(array('did' => $results));
            }
        }
        catch(Exception $e) { }
    }
    
    public function viewAction ()
    {
        $documentsModels = new Models_Documents;
        $languagesModels = new Models_Languages;
        
        $id = $this->_getParam('id', null);
        $documents = array();
        
        foreach($languagesModels->getLanguages() as $language)
        {
            $document = $documentsModels->getDocument(array('did' => $id), $language['lid']);
            
            if($document)
            {
                $documents[] = array(
                    'language_title' => $language['title'],
                    'language_code' => $language['code'],
                    'document'       => $document
                );
            }
        }
        
        if(!$documents)
        {
            $this->addFlashMessage('Folder nie został znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'documents'), 'admin', true);
        }               
        
        $this->view->documents = $documents;
        $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($documents[0]['parent_id']);
    }

    public function createDirectoryAction ()
    {
        $this->checkAccess('intranet-documents-createdirectory');
        
        $documentsModels = new Models_Documents;
                
        $id = $this->_getParam('id', null);
        
        $cmsForm = new Cms_Form('intranet-documents-adddirectory');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $documentsTree = $documentsModels->getDocumentsTree(array('only_dirs' => true));
        $cmsForm->getElement('parent_id')->setMultiOptions($documentsTree)->setValue($id);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $documentsModels->addDirectory($formData);
                    
                    $this->addFlashMessage('Folder został dodany', Cms_Form::SUCCESS);
                    $this->redirect(array('controller' => 'documents', 'action' => 'index', 'id' => ($formData['parent_id'] == 0 ? null : $formData['parent_id'])), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania dokumentu.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($id);
        }
        
        $this->view->id = $id;
        $this->view->cmsForm = $cmsForm;
    }
    
    public function createDocumentAction ()
    {
        $this->checkAccess('intranet-documents-createdocument');
        
        $documentsModels = new Models_Documents;
                
        $id = $this->_getParam('id', null);
        
        $cmsForm = new Cms_Form('intranet-documents-adddocument');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $documentsTree = $documentsModels->getDocumentsTree(array('only_dirs' => true));
        $cmsForm->getElement('parent_id')->setMultiOptions($documentsTree)->setValue($id);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $documentsModels->addDocument($formData);
                    
                    $this->addFlashMessage('Dokument został dodany', Cms_Form::SUCCESS);
                    $this->redirect(array('controller' => 'documents', 'action' => 'index', 'id' => ($formData['parent_id'] == 0 ? null : $formData['parent_id'])), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania dokumentu.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($id);
        }
        
        $this->view->id = $id;
        $this->view->cmsForm = $cmsForm;
    }

    public function editDirectoryAction ()
    {
        $documentsModels = new Models_Documents;
        $documentsTranslationModels = new Models_DocumentsTranslation;
        
        $id = $this->_getParam('id', null);
        $document = $documentsModels->getDocument(array('did' => $id));

        if(!$document)
        {
            $this->addFlashMessage('Folder nie został znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'documents'), 'admin', true);
        }        
        
        if($this->isAllowed('intranet-documents-editdirectory') == false)
        {
            if($this->isAllowed('intranet-documents-editowndirectory') == true)
            {
                if($this->identity->uid != $document['uid'])
                {
                    $this->accessError('Brak uprawnień do edycji tego folderu!');
                }
            }
            else
            {
                $this->accessError('Brak uprawnień do edycji tego folderu!');
            }
        }
        
        $cmsForm = new Cms_Form('intranet-documents-adddirectory');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $documentsTranslation = $documentsTranslationModels->getTranslations(array('did' => $id));

        $cmsForm->populate($document + $documentsTranslation);
        
        $documentsTree = $documentsModels->getDocumentsTree(array('only_dirs' => true));
        $cmsForm->getElement('parent_id')->setMultiOptions($documentsTree);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $documentsModels->editDirectory($formData, $id);
                    
                    $this->addFlashMessage('Folder został zapisany', Cms_Form::SUCCESS);
                    
                    $this->redirect(array('controller' => 'documents', 'action' => 'index', 'id' => ($formData['parent_id'] == 0 ? null : $formData['parent_id'])), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania dokumentu.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->document = $document;
            $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($document['parent_id']);
        }
        
        $this->view->cmsForm = $cmsForm;
    }
    
    public function editDocumentAction ()
    {
        $documentsModels = new Models_Documents;
        $documentsTranslationModels = new Models_DocumentsTranslation;
        
        $id = $this->_getParam('id', null);
        $document = $documentsModels->getDocument(array('did' => $id));

        if(!$document)
        {
            $this->addFlashMessage('Dokument nie został znaleziony!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'documents'), 'admin', true);
        }         
        
        if($this->isAllowed('intranet-documents-editdocument') == false)
        {
            if($this->isAllowed('intranet-documents-editowndocument') == true)
            {
                if($this->identity->uid != $document['uid'])
                {
                    $this->accessError('Brak uprawnień do edycji tego dokumentu!');
                }
            }
            else
            {
                $this->accessError('Brak uprawnień do edycji tego dokumentu!');
            }
        }
        
        $cmsForm = new Cms_Form('intranet-documents-adddocument');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        $documentsTranslation = $documentsTranslationModels->getTranslations(array('did' => $id));

        $cmsForm->populate($document + $documentsTranslation);
        
        $documentsTree = $documentsModels->getDocumentsTree(array('only_dirs' => true));
        $cmsForm->getElement('parent_id')->setMultiOptions($documentsTree);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $documentsModels->editDocument($formData, $id);
                    
                    $this->addFlashMessage('Dokument został zapisany', Cms_Form::SUCCESS);
                    
                    $this->redirect(array('controller' => 'documents', 'action' => 'index', 'id' => ($formData['parent_id'] == 0 ? null : $formData['parent_id'])), 'admin', true);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania dokumentu.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }
        
        if($id)
        {
            $this->view->document = $document;
            $this->view->breadcrumbs = $documentsModels->getBreadcrumbs($document['parent_id']);
        }
        
        $this->view->cmsForm = $cmsForm;
    }

    public function deleteAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if($this->isAllowed('intranet-documents-removedirectory') == false && $this->isAllowed('intranet-documents-removeowndirectory') == false && $this->isAllowed('intranet-documents-removedocument') == false && $this->isAllowed('intranet-documents-removeowndocument') == false)
            {
                $this->accessError('Brak uprawnień do usuwania elementów w module dokumenty!');
            }
            
            $documentsModels = new Models_Documents;
            $documents = $this->_getParam('id', null);

            $toDelete = array();

            if(count($documents) > 0)
            {
                foreach($documents as $document)
                {
                    $toDelete[] = $document['value'];
                }
            }
            

            

            if($toDelete)
            {      
                $accessErrors = false;
                
                if($this->isAllowed('intranet-documents-removedirectory') == false && $this->isAllowed('intranet-documents-removeowndirectory') == true)
                {
                    $directoriesToDelete = $documentsModels->getDocuments(array('did' => $toDelete, 'type' => 'DIR'));
                
                    if($directoriesToDelete)
                    {
                        foreach($directoriesToDelete as $directoryToDelete)
                        {
                            if($this->identity->uid != $directoryToDelete['uid'])
                            {
                                $accessErrors = true;
                            }
                        }
                    }
                }
                
                if($this->isAllowed('intranet-documents-removedocument') == false && $this->isAllowed('intranet-documents-removeowndocument') == true)
                {
                    $documentsToDelete = $documentsModels->getDocuments(array('did' => $toDelete, 'type' => 'DOC'));
                
                    if($documentsToDelete)
                    {
                        foreach($documentsToDelete as $documentToDelete)
                        {
                            if($this->identity->uid != $documentToDelete['uid'])
                            {
                                $accessErrors = true;
                            }
                        }
                    }
                }
                

                if($accessErrors == false)
                {
                    try     
                    {
                        $documentsModels->deleteDocuments($toDelete);                         
                        $result = array('message' => 'Rekordy zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                    }
                    catch(Cms_Exception $e)
                    {
                        $result = array('message' => 'Błąd podczas usuwania.', 'type' => 'error');
                    }
                }
                else
                {
                    $result = array('message' => 'Brak uprawnień do usunięcia wybranych dokumentów/katalogów!', 'type' => 'alert');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
}

