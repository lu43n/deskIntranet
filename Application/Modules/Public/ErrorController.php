<?php

class ErrorController extends Cms_Controller_Action
{

    /**
     * Wyświetl błąd dostępu
     * 
     * @return void
     */
    public function accessAction () { }
    
    /**
     * Wyświetl błąd 500/404
     * 
     * @return void
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors || !$errors instanceof ArrayObject) 
        {
            $this->view->message = 'Dotarłeś do strony wyświetlania błędów';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // Błąd 404 - strona nie została odnaleziona
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Strona nie została odnaleziona';
                break;
            default:
                // Domyślnie błąd aplikacji (500)
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Błąd aplikacji';
                break;
        }
        
        // Zapisz zdarzenie do dziennika
        $this->log($this->view->message, $priority, $errors->exception);
        $this->log('Request Parameters', $priority, $errors->request->getParams());

        // Jeśli można to wyświetl błędy
        if ($this->getInvokeArg('displayExceptions') == true) 
        {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request = $errors->request;
    }
}

