<?php

class Admin_DictionaryController extends Cms_Controller_Action_Admin
{
    public function preDispatch() 
    {
        $this->checkAccess('intranet-dictionary');
    }
    
    public function indexAction ()
    {
        $dictionaryModels = new Models_Dictionary;
        $this->view->words = $dictionaryModels->getWords();        
    }
    
    public function searchAction ()
    {
        $dictionaryModels = new Models_Dictionary;
        
        $this->view->keyword = $keyword = $this->_getParam('keyword', null);
        
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Dictionary'));
            $results = $cmsSearch->find($keyword);

            if($results)
            {
                $this->view->words = $dictionaryModels->getWords(array('did' => $results));
            }
        }
        catch(Exception $e) { }
    }
    
    public function addAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-dictionary-add'))
        {
            $this->accessError('Nie posiadasz uprawnień do dodawania definicji!');
        }
        
        $dictionaryModels = new Models_Dictionary;
                
        $dictionaryForm = new Cms_Form('intranet-dictionary');    
        $dictionaryForm->setAction($this->getRequest()->getRequestUri())
                       ->setMethod('post');
                
        if($this->getRequest()->isPost())
        {
            if($dictionaryForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $dictionaryForm->getValues();
                    
                    $dictionaryModels->addWord($formData);
                    $dictionaryForm->addMessage('Tłumaczenie zostało dodane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $dictionaryForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $dictionaryForm->addMessage('Błąd podczas dodawania tłumaczenia.', Cms_Form::SUCCESS);
                }
            }

            if($dictionaryForm->isErrors())
            {
                $dictionaryForm->populate($_POST);
            }
        }

        $this->view->dictionaryForm = $dictionaryForm;
    }

    public function editAction ()
    {
        if(!$this->acl->isUserAllowed('intranet-dictionary-edit'))
        {
            $this->accessError('Nie posiadasz uprawnień do edycji definicji!');
        }
        
        $dictionaryModels = new Models_Dictionary;
        $dictionaryTranslationModels = new Models_DictionaryTranslation;
        
        $id = $this->_getParam('id', null);
        $word = $dictionaryModels->getWord(array('did' => $id));

        if(!$word)
        {
            $this->addFlashMessage('Tłumaczenie nie zostało znalezione!', Cms_Form::ALERT);
            $this->redirect(array('controller' => 'dictionary'), 'admin', true);
        } 
        
        $dictionaryForm = new Cms_Form('intranet-dictionary');    
        $dictionaryForm->setAction($this->getRequest()->getRequestUri())
                       ->setMethod('post');

        $wordTranslations = $dictionaryTranslationModels->getWordTranslations(array('did' => $id));

        $dictionaryForm->populate($word + $wordTranslations);
        
        if($this->getRequest()->isPost())
        {
            if($dictionaryForm->isValid($_POST))
            {                           
                try
                {                    
                    $formData = $dictionaryForm->getValues();
                    
                    $dictionaryModels->editWord($formData, $id);
                    $dictionaryForm->addMessage('Tłumaczenie zostało zapisane.', Cms_Form::SUCCESS);
                }
                catch (Cms_Form_Exception $e)
                {
                    $dictionaryForm->addMessage($e->getMessage(), Cms_Form::ERROR);
                }
                catch (Cms_Exception $e)
                {
                    $dictionaryForm->addMessage('Błąd podczas edycji tłumaczenia.', Cms_Form::SUCCESS);
                }
            }

            if($dictionaryForm->isErrors())
            {
                $dictionaryForm->populate($_POST);
            }
        }

        if($id)
        {
            $this->view->word  = $word;
        }
        
        $this->view->dictionaryForm = $dictionaryForm;
    }   
    
    public function deleteWordsAction ()
    {      
        $this->_helper->viewRenderer->setNoRender();
        
        if($this->getRequest()->isXmlHttpRequest())
        {
            if(!$this->acl->isUserAllowed('intranet-dictionary-remove'))
            {
                
                $result = array('message' => 'Nie posiadasz uprawnień do usuwania definicji!', 'type' => 'error');
                echo json_encode($result);
                
                return;
            }
            
            $dictionaryModels = new Models_Dictionary;
            $words = $this->_getParam('id', null);

            $toDelete = array();

            if(count($words) > 0)
            {
                foreach($words as $word)
                {
                    $toDelete[] = $word['value'];
                }
            }

            if($toDelete)
            {
                try     
                {
                    $dictionaryModels->deleteWords($toDelete);                         
                    $result = array('message' => 'Tłuaczenia zostały usunięte.', 'type' => 'success', 'id' => $toDelete);
                }
                catch(Cms_Exception $e)
                {
                    $result = array('message' => 'Błąd podczas usuwania tłumaczeń.', 'type' => 'error');
                }
            }
            else
            {
                $result = array('message' => 'Błąd podczas usuwania tłumaczeń.', 'type' => 'error');
            }

            echo json_encode($result);
        }
    }
}

