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

interface Cms_Interface_Users
{
    /**
     * Metoda sprawdzająca poprawność danych autoryzacyjnych
     * 
     * @param string username Nazwa użytkownika
     * @param strong password Hasło
     * @return object
     */
    public function authenticate($username, $password);
}