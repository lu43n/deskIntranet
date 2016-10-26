<?php
class Models_SettingsGroups extends Cms_Db
{
	protected $_name = 'settings_groups';
	protected $_primary = 'sgid';
	protected $_dependentTables = array(
            "Models_SettingsValues",
            "Models_SettingsGroupsTranslation"
        );
        
        
        public function getSettingsGroups ($language = LOCALE_ID)
        {            
            $formsGroupsModels = new Models_FormsGroups;
            
            $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('fg' => $formsGroupsModels->_name))
                            ->joinLeft(
                                    array('fgt' => 'forms_groups_translation'), 
                                    'fgt.fgid = fg.fgid AND fgt.lid = "' .$language. '"', 
                                    array(
                                        'category_title'       => 'fgt.title', 
                                        'category_description' => 'fgt.description'
                                    )
                              )
                            ->where('fg.slug LIKE (?)', 'admin-settings-%')
                            ->group('fg.fgid');
            
            $results = $this->fetchAll($select);

            $settingGroups = false;
            if($results)
            {
                foreach($results as $result)
                {
                    $settingGroups[] = array(
                        'fgid'          =>    $result['fgid'],
                        'title'         =>    $result['category_title'],
                        'description'   =>     $result['category_description']
                    );
                }
            }

            return $settingGroups;
        }
        
        public function getSettingsGroup ($id, $language = LOCALE_ID)
        {            
            $formsGroupsModels = new Models_FormsGroups;
            
            $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('fg' => $formsGroupsModels->_name))
                            ->joinLeft(
                                    array('fgt' => 'forms_groups_translation'), 
                                    'fgt.fgid = fg.fgid AND fgt.lid = "' .$language. '"', 
                                    array(
                                        'category_title'       => 'fgt.title', 
                                        'category_description' => 'fgt.description'
                                    )
                              )
                            ->where('fg.fgid = ?', $id)
                            ->group('fg.fgid');
            
            $result = $this->fetchRow($select);

            if($result)
            {
                return array(
                    'fgid'          =>    $result['fgid'],
                    'title'         =>    $result['category_title'],
                    'description'   =>    $result['category_description'],
                    'slug'          =>    $result['slug']
                );
            }
            else
            {
                return false;
            }
        }
        

}
?>
