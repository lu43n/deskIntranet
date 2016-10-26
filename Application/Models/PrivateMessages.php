<?php
class Models_PrivateMessages extends Cms_Db
{
    protected $_name = 'private_messages';
    protected $_primary = 'pmid';

    public function getAutocompleteUsers ($options)
    {
        $users = array();
  
        $usersModel = new Models_Users;
        
        $select = $usersModel->select()
                             ->setIntegrityCheck(false)
                             ->from(array('u' => 'users', array('*')))
                             ->joinLeft(array('ug' => 'users_groups'), 'u.uid = ug.uid', array('ug.gid'))
                             ->joinLeft(array('ud' => 'users_data'), 'u.uid = ud.uid', array('firstname','lastname','mobile'))
                             ->where('u.is_active = 1')
                             ->where('u.is_deleted = 0')
                             ->group('u.uid');

        if(isset($options['uid']))
        {
            $select->where('u.uid <> ?', $options['uid']);
        }
        
        if($options['term'] != null)
        {
            $select->where('firstname LIKE ? OR lastname LIKE ? OR username LIKE ? OR CONCAT(firstname," ",lastname) LIKE ? OR CONCAT(lastname," ",firstname) LIKE ?', '%'.$options['term'].'%');
            $results = $this->fetchAll($select);
            
            if($results)
            {
                foreach($results as $result)
                {
                    $users[] = array(
                        'label'          =>   $result['firstname']. ' ' .$result['lastname'] . ' <'. $result['username'] .'>',
                        'value'          =>   $result['uid']
                    );
                }
            }
        }
        elseif($options['id'] != null)
        {
            $select->where('u.uid = ?', $options['id']);
            $result = $this->fetchRow($select);

            if($result)
            {
                $users = array(
                    'label'          =>   $result['firstname']. ' ' .$result['lastname'] . ' <'. $result['username'] .'>',
                    'value'          =>   $result['uid']
                );
            }
        }
        else 
        {
            return null;
        }            

        return json_encode($users);
    }
    
    public function getMessages ($options)
    {
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('pm' => $this->_name, array('*')))
                       ->joinLeft(array('u' => 'users'), 'u.uid = pm.recipient')
                       ->joinLeft(array('ud' => 'users_data'), 'u.uid = ud.uid', array('firstname','lastname','mobile'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = pm.sender')
                       ->joinLeft(array('ud2' => 'users_data'), 'u2.uid = ud2.uid', array('sender_firstname' => 'firstname', 'sender_lastname' => 'lastname', 'sender_mobile' => 'mobile'))
                       ->group('pm.pmid');

        if(isset($options['orderby']))
        {
            $select->order($options['orderby']);
        }
        
        if(isset($options['recipient_received_at']))
        {
            if($options['recipient_received_at'] == false)
            {
                $select->where('pm.recipient_received_at IS NULL');
            }
        }
        
        if(isset($options['sender']))
        {
            $select->where('pm.sender = ?', $options['sender']);
        }
        
        if(isset($options['recipient']))
        {
            $select->where('pm.recipient = ?', $options['recipient']);
        }
        
        if(isset($options['deleted_by_sender']))
        {
            $select->where('pm.deleted_by_sender = ?', $options['deleted_by_sender']);
        }
        
        if(isset($options['deleted_by_recipient']))
        {
            $select->where('pm.deleted_by_recipient = ?', $options['deleted_by_recipient']);
        }
        
        if(isset($options['sender_or_recipient']))
        {
            $select->where('pm.sender = ? OR pm.recipient = ?', $options['sender_or_recipient']);
        }
        
        if(isset($options['parent_id']))
        {
            if($options['parent_id'] == null)
            {
                $select->where('pm.parent_id IS NULL');
            }
            else
            {
                $select->where('pm.parent_id = ?', $options['parent_id']);
            }
        }

        $results = $this->fetchAll($select);

        if($results)
        {
            foreach($results as $result)
            {
                $messages[] = array(
                    'pmid'                      =>   $result['pmid'],
                    'recipient'                 =>   $result['recipient'],
                    'recipient_name'            =>   $result['firstname'] . ' ' . $result['lastname'],
                    'sender'                    =>   $result['sender'],
                    'sender_name'               =>   $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                    'attachments'               =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                    'subject'                   =>   $result['subject'],
                    'message'                   =>   $result['message'],
                    'sent_at'                   =>   $result['sent_at'],
                    'sender_received_at'        =>   $result['sender_received_at'],
                    'recipient_received_at'     =>   $result['recipient_received_at'],
                    'deleted_by_recipient'       =>   $result['deleted_by_recipient'],
                    'deleted_by_sender'          =>   $result['deleted_by_sender'],
                    'replies'                   =>   $this->getMessages(array('parent_id' => $result['pmid']))
                );
            }
        }

        return (isset($messages) ? $messages : false);
    }
    
    public function getMessage ($options)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('pm' => $this->_name, array('*')))
                       ->joinLeft(array('u' => 'users'), 'u.uid = pm.recipient')
                       ->joinLeft(array('ud' => 'users_data'), 'u.uid = ud.uid', array('recipient_firstname' => 'firstname', 'recipient_lastname' => 'lastname', 'recipient_mobile' => 'mobile'))
                       ->joinLeft(array('u2' => 'users'), 'u2.uid = pm.sender')
                       ->joinLeft(array('ud2' => 'users_data'), 'u2.uid = ud2.uid', array('sender_firstname' => 'firstname', 'sender_lastname' => 'lastname', 'sender_mobile' => 'mobile'))
                       ->group('pm.pmid')
                       ->order('pm.sent_at DESC');

        if(isset($options['recipient']))
        {
            $select->where('pm.recipient = ?', $options['recipient']);
        }
        
        if(isset($options['pmid']))
        {
            $select->where('pm.pmid = ?', $options['pmid']);
        }
        
        if(isset($options['sender_or_recipient']))
        {
            $select->where('pm.sender = ? OR pm.recipient = ?', $options['sender_or_recipient']);
        }
        
        $result = $this->fetchRow($select);

        if($result)
        {
            $message = array(
                'pmid'                       =>   $result['pmid'],
                'recipient'                  =>   $result['recipient'],
                'recipient_name'             =>   $result['recipient_firstname'] . ' ' . $result['recipient_lastname'],
                'sender'                     =>   $result['sender'],
                'sender_name'                =>   $result['sender_firstname'] . ' ' . $result['sender_lastname'],
                'attachments'                =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                'subject'                    =>   $result['subject'],
                'message'                    =>   $result['message'],
                'sent_at'                    =>   $result['sent_at'],
                'sender_received_at'         =>   $result['sender_received_at'],
                'recipient_received_at'      =>   $result['recipient_received_at'],
                'deleted_by_recipient'       =>   $result['deleted_by_recipient'],
                'deleted_by_sender'          =>   $result['deleted_by_sender']
            );
        }

        return (isset($message) ? $message : false);
    }
    
    public function sendMessage ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            if($formData['parent_id'])
            {
                $options = array(
                    'pmid'       =>  $formData['parent_id'],
                    'recipient'  =>  $formData['sender']
                );
                
                $parentMessage = $this->getMessage($options);
            }
            
            $data = array(
                'sender'                   => $formData['sender'],
                'recipient'                => $formData['recipient'],
                'parent_id'                => (isset($parentMessage) && $parentMessage != false ? $parentMessage['pmid'] : null),
                'attachments'              => (isset($formData['attachments']) ? $formData['attachments'] : null),
                'subject'                  => $formData['subject'],
                'message'                  => $formData['message'],
                'sent_at'                  => new Zend_Db_Expr('NOW()'),
                'sender_received_at'       => new Zend_Db_Expr('NOW()'),
                'recipient_received_at'    => null
            );

            $this->insert($data);

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    
    public function deleteMessages ($messages, $uid)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            if(is_array($messages))
            {
                $where1 = $this->getAdapter()->quoteInto('pmid IN(?)', $messages);
            }
            else
            {
                $where1 = $this->getAdapter()->quoteInto('pmid = ?', $messages);
            }
            
            $where2 = $this->getAdapter()->quoteInto('parent_id IS NULL');
            
            
            if($this->fetchAll(array($where1, $where2, $this->getAdapter()->quoteInto('recipient = ?', $uid)))->count())
            {
                $data = array('deleted_by_recipient' => 1);
                
                $where3 = $this->getAdapter()->quoteInto('recipient = ?', $uid);
            }
            elseif($this->fetchAll(array($where1, $where2, $this->getAdapter()->quoteInto('sender = ?', $uid)))->count())
            {
                $data = array('deleted_by_sender' => 1);
                
                $where3 = $this->getAdapter()->quoteInto('sender = ?', $uid);
            }
            
            if(isset($data))
            {
                $this->update($data, array($where1, $where2, $where3));
            }
            
            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    
    public function setReaded ($pmId, $uid)
    {
        $this->getAdapter()->beginTransaction();
        try
        {           
            $data = array(
                'recipient_received_at' => new Zend_Db_Expr('NOW()')
            );
            
            $where1 = $this->getAdapter()->quoteInto('(pmid = ? OR parent_id = ?)', $pmId);
            $where2 = $this->getAdapter()->quoteInto('sender <> ?', $uid);
            
            $this->update($data, array($where1, $where2));

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
}
?>
