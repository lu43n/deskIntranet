<?php
class Models_UsersData extends Cms_Db
{
	protected $_name = 'users_data';
	protected $_primary = 'udid';
	protected $_dependentTables = array(
            "Models_Users"
        );
}
?>
