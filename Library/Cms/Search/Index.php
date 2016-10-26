<?php

class Cms_Search_Index
{
    public static function getIndex($indexDirectory) 
    {
        $directory = SYSTEM_PATH . DS . 'Cache' . DS . 'Search' . DS . $indexDirectory . DS;
        if(!is_dir($directory))
        {
            return Zend_Search_Lucene::create($directory);
        }
        else
        {
            return Zend_Search_Lucene::open($directory);
        }
    }
}

?>
