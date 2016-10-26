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

class Cms_Translate_Adapter_Db extends Zend_Translate_Adapter
{
    private $_data = array();

    /**
     * Załaduj listę translacji
     *
     * @param  string|array  $data
     * @param  string        $locale  Kod języka, do któego translacje będą przypisane
     * @param  array         $options Opcje dodatkowe
     * @return array
     */
    protected function _loadTranslationData($data, $locale, array $options = array())
    {
        $this->_data = array();
        $translations = array();
        
        $dictionaryModels = new Models_DictionaryTranslation();
        
        $data = $dictionaryModels->getDictonary(array(
                    'language_code' => $locale
                ));
        
        foreach($data as $translation)
        {
            $translations[$translation['key']] = $translation['value'];
        }

        if (!is_array($translations)) 
        {
            require_once 'Zend/Translate/Exception.php';
            throw new Zend_Translate_Exception("Error including array or file '".$data."'");
        }

        if (!isset($this->_data[$locale])) 
        {
            $this->_data[$locale] = array();
        }

        $this->_data[$locale] = $translations + $this->_data[$locale];
        return $this->_data;
    }

    /**
     * Zwraca nazwę adaptera
     *
     * @return string
     */
    public function toString()
    {
        return "Db";
    }
}
