<?php

class Cms_Log extends Zend_Log implements Zend_Log_FactoryInterface
{
    const ERROR = 'ERROR';
    const NOTICE = 'NOTICE';
    const WARNING = 'WARNING';
    const ALERT = 'ALERT';

    public static function addError ($msg, $type = self::NOTICE)
    {
        $loggerModels = new Models_Logger();
        
        $data = array(
            'message' => $msg,
            'type' => $type,
            'date' => new Zend_Db_Expr('NOW()')
        );

        $loggerModels->insert($data);
    }
}

?>
