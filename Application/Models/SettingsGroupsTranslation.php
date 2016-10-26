<?php
class Models_SettingsGroupsTranslation extends Cms_Db
{
	protected $_name = 'settings_groups_translation';
	protected $_primary = 'sgtid';
	protected $_referenceMap = array(
            "SettingsGroupsTranslation" => array(
                "columns" => array("sgid"),
                "refTableClass" => "Models_SettingsGroups",
                "refColumns" => array("sgid")
            )
        );
}
?>
