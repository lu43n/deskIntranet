<?php

class Cms_Validate_EmailAddress extends Zend_Validate_EmailAddress
{
    protected $_messageTemplates = array(
        self::INVALID            => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::INVALID_FORMAT     => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::INVALID_HOSTNAME   => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::INVALID_MX_RECORD  => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::INVALID_SEGMENT    => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::DOT_ATOM           => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::QUOTED_STRING      => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::INVALID_LOCAL_PART => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
        self::LENGTH_EXCEEDED    => '[admin-forms] Pole "%s" zawiera niepoprawny adres email.',
    );
}

?>
