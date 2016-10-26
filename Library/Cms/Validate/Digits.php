<?php

class Cms_Validate_Digits extends Zend_Validate_Digits
{
    protected $_messageTemplates = array(
        self::NOT_DIGITS   => 'Pole "%s" musi zawierać tylko liczby.',
        self::STRING_EMPTY => 'Pole "%s" musi zawierać tylko liczby.',
        self::INVALID      => 'Pole "%s" musi zawierać tylko liczby.',
    );
}

?>
