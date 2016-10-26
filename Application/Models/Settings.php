<?php
class Models_Settings extends Cms_Db
{
	protected $_name = 'settings';
	protected $_primary = 'sid';
        

        public function getSettingsGroups ($language = LOCALE_ID)
        {
            $formsModels = new Models_Forms;
            
            $settingsGroups = array();
            
            $settings = $formsModels->getForm(array('slug' => 'intranet-settings'));    

            if($settings)
            {
                $settingsGroups = $formsModels->getForms(array('parent_id' => $settings['fid']));
            }
            
            return $settingsGroups;
        }
        
        public function getSettings ()
        {
            $select = $this->select()
                           ->from(array('s' => $this->_name));
            
            return $this->fetchAll($select);
        }
        
        public function getSetting ($name)
        {
            $name = str_replace('*', '%', $name);
            
            $select = $this->select()
                           ->from(array('s' => $this->_name))
                           ->where('name LIKE (?)', $name);
            
            $results = $this->fetchAll($select);
            
            if($results)
            {
                if(strpos($name, '%') !== false)
                {
                    foreach($results as $result)
                    {
                        $settings[$result['name']] = $result['value'];
                    }
                }
                else
                {
                    return array($results[0]['name'] => $results[0]['value']);
                }
            }
            
            return $settings;
        }
        
        public function getSettingsGroup ($id, $language = LOCALE_ID)
        {            
            $formsModels = new Models_Forms;
            $settingsGroup = $formsModels->getForm(array('fid' => $id));
            
            return $settingsGroup;
        }
        
        public function getSettingsValues ($fieldNames)
        {                        
            $select = $this->select()
                           ->from(array('s' => $this->_name))
                           ->where('s.name IN (?)', $fieldNames);
            
            $results = $this->fetchAll($select);

            $populate = array();
            if($results)
            {
                foreach($results as $result)
                {
                    $populate[$result['name']] = $result['value'];
                }
            }
            
            return $populate;
        }
        
        public function editSettings ($formData)
        {     
            $this->getAdapter()->beginTransaction();
            try
            {
                unset($formData['buttons']);

                $where = $this->getAdapter()->quoteInto('name IN (?)', array_keys($formData));
                $this->delete($where);

                foreach($formData as $key => $val)
                {
                    $data = array(
                        'name'   =>  $key,
                        'value'  =>  $val
                    );

                    $this->insert($data);
                }
                
                $this->getAdapter()->commit();
            }
            catch (Exception $e)
            {
                $this->getAdapter()->rollBack();
            }
        }
}
?>
