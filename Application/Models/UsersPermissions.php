<?php
class Models_UsersPermissions extends Cms_Db
{
	protected $_name = 'users_permissions';
	protected $_primary = 'upid';

        protected $_referenceMap = array(
            "Permissions" => array(
                "columns" => array("pid"),
                "refTableClass" => "Models_Permissions",
                "refColumns" => array("pid")
            ),
            "Users" => array(
                "columns" => array("uid"),
                "refTableClass" => "Models_Users",
                "refColumns" => array("uid")
            )
        );
        
        public function getUsersPermissions ()
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('up' => $this->_name, array('*')))
                           ->joinLeft(array('p' => 'permissions'), 'p.pid = up.pid', array('p.slug'));
                           
            $results = $this->fetchAll($select);
            
            if($results)
            {
                foreach($results as $result)
                {
                    $users_permissions[] = array(
                        'uid'                =>   $result['uid'],
                        'pid'                =>   $result['pid'],
                        'permission_slug'    =>   $result['slug'],
                        'is_allowed'         =>   $result['is_allowed']
                    );
                }
            }
                        
            return (isset($users_permissions) ? $users_permissions : false);
        }
}
?>
