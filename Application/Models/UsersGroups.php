<?php
class Models_UsersGroups extends Cms_Db
{
	protected $_name = 'users_groups';
	protected $_primary = 'ugid';

        protected $_referenceMap = array(
            "Groups" => array(
                "columns" => array("gid"),
                "refTableClass" => "Models_Groups",
                "refColumns" => array("gid")
            ),
            "Users" => array(
                "columns" => array("uid"),
                "refTableClass" => "Models_Users",
                "refColumns" => array("uid")
            )
        );

        public function getUsersForACL ()
        {
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array('ug' => $this->_name), array('uid','groups_ids' => new Zend_Db_Expr('GROUP_CONCAT(ug.gid)')))
                           ->group('ug.uid');

            $results = $this->fetchAll($select);

            if($results)
            {
                foreach($results as $result)
                {
                    $users_groups[] = array(
                        'uid'         =>   $result['uid'],
                        'groups_ids'  =>   $result['groups_ids']
                    );
                }
            }
                        
            return (isset($users_groups) ? $users_groups : false);
        }
}
?>
