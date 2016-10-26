<?php

class Admin_NewsController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-news');
    }
    
    public function indexAction ()
    {
        $newsModels = new Models_News;
        $this->view->newses = $newsModels->getNewses();        
    }
    
    public function viewAction ()
    {
        $newsModels = new Models_News;
        
        $id = $this->_getParam('id', null);
        $news = $newsModels->getNews(array('nid' => $id));

        if(!$news)
        {
            $this->addFlashMessage('Aktualność nie została znaleziona!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'news'), 'admin', true);
        } 
        
        $this->view->news = $news;
    }
    
    public function searchAction ()
    {
        $newsModels = new Models_News;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('News'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->newses = $newsModels->getNewses(array('nid' => $results));
            }
        }
        catch(Exception $e) {}
    }
    
    public function addAction ()
    {
        $this->checkAccess('intranet-news-create');
        
        $newsModels = new Models_News;
        
        $cmsForm = new Cms_Form('intranet-news');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    $formData['uid'] = $this->identity->uid;
                    
                    $newsModels->addNews($formData);
                    $cmsForm->addMessage('Aktualność została dodana.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas dodawania aktualności.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }

        $this->view->cmsForm = $cmsForm;
    }

    public function editAction ()
    {
        $newsModels = new Models_News;
        $newsTranslationModels = new Models_NewsTranslation;
        
        $id = $this->_getParam('id', null);
        $news = $newsModels->getNews(array('nid' => $id));

        if(!$news)
        {
            $this->addFlashMessage('Aktualność nie została znaleziona!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'news'), 'admin', true);
        } 
        
        if($this->isAllowed('intranet-news-edit') == false)
        {
            if($this->isAllowed('intranet-news-editown') == true)
            {
                if($this->identity->uid != $news['uid'])
                {
                    $this->accessError('Brak uprawnień do edycji tej aktualności!');
                }
            }
            else
            {
                $this->accessError('Brak uprawnień do edycji tej aktualności!');
            }
        }
        
        $cmsForm = new Cms_Form('intranet-news');    
        $cmsForm->setAction($this->getRequest()->getRequestUri())
                ->setMethod('post');

        $newsTranslations = $newsTranslationModels->getNewsTranslations(array('nid' => $id));

        $cmsForm->populate($news + $newsTranslations);
        
        if($this->getRequest()->isPost())
        {
            if($cmsForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $cmsForm->getValues();
                    
                    $newsModels->editNews($formData, $id);
                    $cmsForm->addMessage('Aktualność została zapisana.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $cmsForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $cmsForm->addMessage('Błąd podczas edycji aktualności.', Cms_Form::SUCCESS);
                }
            }

            if($cmsForm->isErrors())
            {
                $cmsForm->populate($_POST);
            }
        }

        if($news)
        {
            $this->view->news  = $news;
        }
        
        $this->view->cmsForm = $cmsForm;
    }   
    
    public function deleteNewsAction ()
    {    
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if($this->isAllowed('intranet-news-remove') == false && $this->isAllowed('intranet-news-removeown') == false)
            {
                $this->accessError('Brak uprawnień do usuwania aktualności!');
            }
            
            $newsModels = new Models_News;
            $newses = $this->_getParam('id', null);

            $toDelete = array();

            if(count($newses) > 0)
            {
                foreach($newses as $news)
                {
                    $toDelete[] = $news['value'];
                }
            }

            if($toDelete)
            {
                $accessErrors = false;
                
                if($this->isAllowed('intranet-news-remove') == false && $this->isAllowed('intranet-news-removeown') == true)
                {
                    $newses = $newsModels->getNewses(array('nid' => $toDelete));

                    foreach($newses as $news)
                    {
                        if($this->identity->uid != $news['uid'])
                        {
                            $accessErrors = true;
                        }
                    }
                }
                
                if($accessErrors == false)
                {
                    try     
                    {
                        $newsModels->deleteNews($toDelete);                         
                        $result = array('message' => 'Aktualności zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                    }
                    catch(Cms_Exception $e)
                    {
                        $result = array('message' => 'Błąd podczas usuwania aktualności.', 'type' => 'error');
                    }
                }
                else
                {
                    $result = array('message' => 'Brak uprawnień do usunięcia wybranych aktualności!', 'type' => 'alert');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania aktualności.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
}

