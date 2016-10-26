<?php

class Cms_Validate_Required extends Zend_Validate_NotEmpty
{
    protected $_messageTemplates = array(
        self::IS_EMPTY => "[admin-forms] Pole \"%s\" jest wymagane.",
        self::INVALID  => "[admin-forms] Pole \"%s\" zawiera nieprawidłową wartość.",
    );
}

?>
