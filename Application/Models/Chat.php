<?php
class Models_Chat extends Cms_Db
{
    protected $_name = 'chat';
    protected $_primary = 'cid';

    public function getAllChats ($options)
    {
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('c' => $this->_name))
                       ->joinLeft(array('u1' => 'users'), 'u1.uid = c.sender AND u1.is_deleted = 0', array('sender_id' => 'u1.uid'))
                       ->joinLeft(array('ud1' => 'users_data'), 'ud1.uid = u1.uid', array('sender_firstname' => 'ud1.firstname', 'sender_lastname' => 'ud1.lastname'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = c.recipient AND u2.is_deleted = 0', array('recipient_id' => 'u2.uid'))
                       ->joinLeft(array('ud2' => 'users_data'), 'ud2.uid = u2.uid', array('recipient_firstname' => 'ud2.firstname', 'recipient_lastname' => 'ud2.lastname'))
                       ->order('c.sent_at ASC');
        
        if(isset($options))
        {

             
            if(isset($options['search_keyword']))
            {
                $select->where('c.sender = ? OR c.recipient = ?', $options['sender']);
                
                $options['search_keyword'] = str_replace('*', '%', $options['search_keyword']);
                
                $select->where('c.message LIKE ?', $options['search_keyword']);
            }
            else
            {
                if(isset($options['sender']))
                {
                    $select->where('c.sender = ?', $options['sender']);
                }
            }
            
            if(isset($options['group_by_recipient']) && $options['group_by_recipient'] == true)
            {
                $select->group('c.recipient');
            }
        }
                
        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $chats[] = array(
                    'cid'              =>    $result['cid'],
                    'sent_at'          =>    $result['sent_at'],
                    'received_at'      =>    $result['received_at'],
                    'sender_name'      =>    $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                    'sender_id'        =>    $result['sender_id'],
                    'recipient_name'   =>    $result['recipient_firstname'] . ' ' . $result['recipient_lastname'],
                    'recipient_id'     =>    $result['recipient_id'],
                    'message'          =>    preg_replace_callback('~((?:https?://|www\d*\.)\S+[-\w+&@#/%=\~|])~', array($this, 'parse_links'), $result['message'])
                );

            }
        }
        
        return (isset($chats) ? $chats : array());
    }
    
    public function getChat ($options)
    {
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('c' => $this->_name))
                       ->joinLeft(array('u1' => 'users'), 'u1.uid = c.sender AND u1.is_deleted = 0', array('sender_id' => 'u1.uid'))
                       ->joinLeft(array('ud1' => 'users_data'), 'ud1.uid = u1.uid', array('sender_firstname' => 'ud1.firstname', 'sender_lastname' => 'ud1.lastname'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = c.recipient AND u2.is_deleted = 0', array('recipient_id' => 'u2.uid'))
                       ->joinLeft(array('ud2' => 'users_data'), 'ud2.uid = u2.uid', array('recipient_firstname' => 'ud2.firstname', 'recipient_lastname' => 'ud2.lastname'))
                       ->group('c.cid');
        
        if(isset($options))
        {
            if(isset($options['cid']))
            {
                $select->where('c.cid = ?', $options['cid']);
            }
        }
        else
        {
            return array();
        }
        
        
        $result = $this->fetchRow($select);

        if($result)
        {
            $chat = array(
                'cid'              =>    $result['cid'],
                'sent_at'          =>    $result['sent_at'],
                'received_at'      =>    $result['received_at'],
                'sender_name'      =>    $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                'sender_id'        =>    $result['sender_id'],
                'recipient_name'   =>    $result['recipient_firstname'] . ' ' . $result['recipient_lastname'],
                'recipient_id'     =>    $result['recipient_id'],
                'message'          =>    preg_replace_callback('~((?:https?://|www\d*\.)\S+[-\w+&@#/%=\~|])~', array($this, 'parse_links'), $result['message'])
            );
        }
        
        return (isset($chat) ? $chat : array());
    }
    
    public function getChatHistory ($options)
    {
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('c' => $this->_name))
                       ->joinLeft(array('u1' => 'users'), 'u1.uid = c.sender AND u1.is_deleted = 0', array('sender_id' => 'u1.uid'))
                       ->joinLeft(array('ud1' => 'users_data'), 'ud1.uid = u1.uid', array('sender_firstname' => 'ud1.firstname', 'sender_lastname' => 'ud1.lastname'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = c.recipient AND u2.is_deleted = 0', array('recipient_id' => 'u2.uid'))
                       ->joinLeft(array('ud2' => 'users_data'), 'ud2.uid = u2.uid', array('recipient_firstname' => 'ud2.firstname', 'recipient_lastname' => 'ud2.lastname'))
                       ->where('c.sender = ?', $options['sender'])
                       ->where('c.recipient = ?', $options['recipient'])
                       ->orWhere('c.sender = ?', $options['recipient'])
                       ->where('c.recipient = ?', $options['sender'])
                       ->order('c.sent_at ASC');

        if(isset($options))
        {
            if(isset($options['limit']))
            {
                $select->limit('20', $options['limit']);
            }
        }

        $results = $this->fetchAll($select);
        
        if($results)
        {
            foreach($results as $result)
            {
                $chats[] = array(
                    'cid'              =>    $result['cid'],
                    'sent_at'          =>    $result['sent_at'],
                    'received_at'      =>    $result['received_at'],
                    'sender_name'      =>    $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                    'sender_id'        =>    $result['sender_id'],
                    'recipient_name'   =>    $result['recipient_firstname'] . ' ' . $result['recipient_lastname'],
                    'recipient_id'     =>    $result['recipient_id'],
                    'message'          =>    preg_replace_callback('~((?:https?://|www\d*\.)\S+[-\w+&@#/%=\~|])~', array($this, 'parse_links'), $result['message'])
                );

            }
        }

        return (isset($chats) ? $chats : array());
    }
    

    /*
     * Usuwanie konwersacji
     */
    
    public function deleteChats ($chats)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            if(is_array($chats))
            {
                $where = $this->getAdapter()->quoteInto('recipient IN(?)', $chats);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('recipient = ?', $chats);
            }

            $this->delete($where);

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }     
    
    
    protected function parse_links  ( $m )
    {
        $href = $name = html_entity_decode($m[0]);

        if ( strpos( $href, '://' ) === false ) {
            $href = 'http://' . $href;
        }

        if( strlen($name) > 40 ) {
            $k = ( 40 - 3 ) >> 1;
            $name = substr( $name, 0, $k ) . '...' . substr( $name, -$k );
        }

        return sprintf( '<a href="%s" target="_blank">%s</a>', htmlentities($href), htmlentities($name) );
    }

    public function setUserActivity ($uid)
    {
        $usersModels = new Models_Users;
        
        if($uid)
        {
            $data = array(
                'last_active_at'   =>   new Zend_Db_Expr('NOW()')
            );

            $where = $this->getAdapter()->quoteInto('uid = ?', $uid);

            $usersModels->update($data, $where);
        }
    }
    
    public function getUsersList ($uid)
    {
        $usersModels = new Models_Users;
        
        $users = $usersModels->getUsers();
        
        if($users)
        {
            foreach($users as $k => $v)
            {
                if($v['uid'] == $uid)
                {
                    unset($users[$k]);
                    continue;
                }
                
                $users[$k] = $v;
                $users[$k]['state'] = ( $v['last_active_at'] != null && (strtotime($v['last_active_at']) > strtotime('-3 minutes', time()))  ? 'active' : 'inactive' );
                
            }
        }
        
        return $users;
    }
    
    public function addMessage ($formData)
    {
        $data = array(
            'sender'          =>   $formData['sender'],
            'recipient'       =>   $this->getAdapter()->quote($formData['recipient'], Zend_Db::INT_TYPE),
            'message'         =>   strip_tags($formData['message']),
            'sent_at'         =>   new Zend_Db_Expr('NOW()'),
            'received_at'     =>   null
        );

        return $this->insert($data);
    }
    
    public function getNewChatMessages ($id)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('c' => $this->_name))
                       ->joinLeft(array('u1' => 'users'), 'u1.uid = c.sender AND u1.is_deleted = 0', array('sender_id' => 'u1.uid'))
                       ->joinLeft(array('ud1' => 'users_data'), 'ud1.uid = u1.uid', array('sender_firstname' => 'ud1.firstname', 'sender_lastname' => 'ud1.lastname'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = c.recipient AND u2.is_deleted = 0', array('recipient_id' => 'u2.uid'))
                       ->joinLeft(array('ud2' => 'users_data'), 'ud2.uid = u2.uid', array('recipient_firstname' => 'ud2.firstname', 'recipient_lastname' => 'ud2.lastname'))
                       ->where('c.recipient = ?', $id)
                       ->where('c.received_at IS NULL')
                       ->order('c.sent_at ASC')
                       ->group('c.cid');

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $chats[] = array(
                    'cid'              =>    $result['cid'],
                    'sent_at'          =>    $result['sent_at'],
                    'received_at'      =>    $result['received_at'],
                    'sender_name'      =>    $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                    'sender_id'        =>    $result['sender_id'],
                    'recipient_name'   =>    $result['recipient_firstname'] . ' ' . $result['recipient_lastname'],
                    'recipient_id'     =>    $result['recipient_id'],
                    'message'          =>    preg_replace_callback('~((?:https?://|www\d*\.)\S+[-\w+&@#/%=\~|])~', array($this, 'parse_links'), $result['message']),
                    'count'            =>    count($results)
                );

                $toUpdate[] = $result['cid'];
            }
            
            if(isset($toUpdate) && count($toUpdate) > 0)
            {
                $where = $this->getAdapter()->quoteInto('cid IN(?)', $toUpdate);
                
                $this->update(array('received_at' => new Zend_Db_Expr('NOW()')), $where);
            }
        }

        return (isset($chats) ? $chats : array());
    }
}
?>
