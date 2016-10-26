<?php
class Models_GroupsPermissions extends Cms_Db
{
	protected $_name = 'groups_permissions';
	protected $_primary = 'gpid';

        protected $_referenceMap = array(
            "Permissions" => array(
                "columns" => array("pid"),
                "refTableClass" => "Models_Permissions",
                "refColumns" => array("pid")
            ),
            "Groups" => array(
                "columns" => array("gid"),
                "refTableClass" => "Models_Groups",
                "refColumns" => array("gid")
            )
        );
        
        public function getGroupsPermissions ($options = array())
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('gp' => $this->_name, array('*')))
                           ->joinLeft(array('p' => 'permissions'), 'p.pid = gp.pid', array('permission_slug' => 'p.slug'))
                           ->joinLeft(array('g' => 'groups'), 'g.gid = gp.gid', array('group_slug' => 'g.slug'));
                           
            if(isset($options['gid']))
            {
                if(is_array($options['gid']))
                {
                    $select->where('gp.gid IN (?)', $options['gid']);
                }
            }
            
            $results = $this->fetchAll($select);

            if($results)
            {
                foreach($results as $result)
                {
                    $groups_permissions[] = array(
                        'gid'                =>   $result['gid'],
                        'pid'                =>   $result['pid'],
                        'permission_slug'    =>   $result['permission_slug'],
                        'group_slug'         =>   $result['group_slug'],
                        'is_allowed'         =>   $result['is_allowed']
                    );
                }
            }
            
                        
            return (isset($groups_permissions) ? $groups_permissions : false);
        }
}
?>
