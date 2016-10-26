<?php
class Models_Events extends Cms_Db
{
    protected $_name = 'events';
    protected $_primary = 'eid';

    public function getEvents ($options = array(), $language = LOCALE_ID)
    {
        $usersModels = new Models_Users;
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('e' => $this->_name))
                       ->joinLeft(array('et' => 'events_translation'), 'et.eid = e.eid AND et.lid = "'.$language.'"', array('title' => 'et.title', 'attachments' => 'et.attachments', 'content' => 'et.content'))
                       ->group('e.eid');

        if(isset($options) && is_array($options))
        {
            if(isset($options['eid']))
            {
                if(is_array($options['eid']))
                {
                    $select->where('e.eid IN (?)', $options['eid']);
                }
            }
            
            if(isset($options['limit']))
            {
                $select->limit($options['limit']);
            }
            
            if(isset($options['order']))
            {
                $select->order('e.'.$options['order']);
            }
            
            if(isset($options['type']))
            {
                $select->where('e.type = ?', $options['type']);
            }
            
            if(isset($options['date']))
            {
                if($options['date'] == 'comming')
                {
                    $select->where('(e.starting_at > NOW() AND e.ending_at IS NULL) OR (e.starting_at < NOW() AND e.ending_at > NOW())');
                }
                elseif($options['date'] == 'past')
                {
                    $select->where('(e.starting_at < NOW() AND e.ending_at IS NULL) OR (e.starting_at < NOW() AND e.ending_at < NOW())');
                }
            }
        }
        
        $results = $this->fetchAll($select);

        $events = array();
        if($results)
        {
            foreach($results as $result)
            {
                $user = $usersModels->getUser(array('uid' => $result['uid']));
                
                $events[$result['eid']] = array(
                    'eid'         =>   $result['eid'],
                    'uid'         =>   $result['uid'],
                    'user'        =>   $user,
                    'type'        =>   $result['type'],
                    'created_at'  =>   $result['created_at'],
                    'starting_at' =>   $result['starting_at'],
                    'ending_at'   =>   $result['ending_at'],
                    'translation' =>   array(
                        'title'       =>   $result['title'],
                        'attachments' =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                        'content'     =>   $result['content'],
                    )
                );
            }
        }

        return $events;
    }
    
    public function getEvent ($options, $language = LOCALE_ID)
    {
        $usersModels = new Models_Users;
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('e' => $this->_name))
                       ->joinLeft(array('et' => 'events_translation'), 'et.eid = e.eid AND et.lid = "'.$language.'"', array('title' => 'et.title','attachments' => 'et.attachments','content' => 'et.content'));

        if(isset($options) && is_array($options))
        {
            if(isset($options['eid']))
            {
                $select->where('e.eid = ?', $options['eid']);
            }
        }

        $result = $this->fetchRow($select);

        $events = array();
        if($result)
        {
            $result = $result->toArray();
            
            $user = $usersModels->getUser(array('uid' => $result['uid']));

            $events = array(
                'eid'         =>   $result['eid'],
                'uid'         =>   $result['uid'],
                'user'        =>   $user,
                'type'        =>   $result['type'],
                'created_at'  =>   $result['created_at'],
                'starting_at' =>   $result['starting_at'],
                'ending_at'   =>   $result['ending_at'],
                'translation' =>   array(
                    'title'       =>   $result['title'],
                    'attachments' =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                    'content'     =>   $result['content'],
                )
            );
        }

        return $events;
    }

    public function addEvent ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
                        
            $eventsData = array(
                'uid'         =>  $formData['uid'],
                'starting_at' =>  date('Y-m-d H:i:00', strtotime($formData['starting_at'])),
                'ending_at'   =>  ($formData['ending_at'] ? date('Y-m-d H:i:00', strtotime($formData['ending_at'])) : null),
                'created_at'  =>  new Zend_Db_Expr('NOW()'),
                'type'        =>  ($formData['type'] ? $formData['type'] : 0)
            );
            
            if($eventsData['ending_at'] != null)
            {
                if(strtotime($formData['starting_at']) > strtotime($formData['ending_at']))
                {
                    throw new Cms_Form_Exception('Niepoprawny czas rozpoczęcia i zakończenia wydarzenia.');
                }
            }

            $last_id = $this->insert($eventsData);

            $usersModels = new Models_Users;
            $user = $usersModels->getUser(array('uid' => $eventsData['uid']));
            
            $eventsTranslationModels = new Models_EventsTranslation;
            $languagesModels         = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Events'));

            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $eventsTranslationData = $formData['languages'][$language['code']];
                    $eventsTranslationData['eid']  = $last_id;
                    $eventsTranslationData['lid']  = $language['lid'];

                    $eventsTranslationModels->insert($eventsTranslationData);             

                    $fields = array(
                        'starting_at' =>   date('Y-d-m H:i:00', strtotime($formData['starting_at'])),   
                        'author'      =>   $user['firstname'].' <'.$user['username'].'> '.$user['lastname'],   
                        'title'       =>   $eventsTranslationData['title'],   
                        'content'     =>   $eventsTranslationData['content']
                    ); 
                    
                    $cmsSearch->addDocument($fields, $last_id);
                }
            }            

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
    

    public function editEvent ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $eventsData = array(
                'starting_at' =>  date('Y-m-d H:i:00', strtotime($formData['starting_at'])),
                'ending_at'   =>  ($formData['ending_at'] ? date('Y-m-d H:i:00', strtotime($formData['ending_at'])) : null),
                'type'        =>  ($formData['type'] ? $formData['type'] : 0)
            );
            
            if($eventsData['ending_at'] != null)
            {
                if(strtotime($formData['starting_at']) > strtotime($formData['ending_at']))
                {
                    throw new Cms_Form_Exception('Niepoprawny czas rozpoczęcia i zakończenia wydarzenia.');
                }
            }
            
            $where = $this->getAdapter()->quoteInto('eid = ?', $id);
            $this->update($eventsData, $where);

            $events = $this->getEvent(array('eid' => $id));
            
            $usersModels = new Models_Users;
            $user = $usersModels->getUser(array('uid' => $events['uid']));
            
            // Zapisywanie translacji

            $eventsTranslationModels = new Models_EventsTranslation;
            $languagesModels         = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Events'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $eventsTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('eid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($eventsTranslationModels->fetchRow($where))
                    {
                        $eventsTranslationModels->update($eventsTranslationData, $where);
                    }
                    else
                    {
                        $data['lid']  = $language['lid'];
                        $data['eid']  = $id;
                        $eventsTranslationModels->insert($data);
                    }           

                    $fields = array(
                        'starting_at' =>   date('Y-d-m H:i:00', strtotime($formData['starting_at'])),   
                        'author'      =>   $user['firstname'].' <'.$user['username'].'> '.$user['lastname'],   
                        'title'       =>   $eventsTranslationData['title'],   
                        'content'     =>   $eventsTranslationData['content']
                    ); 
                    
                    $cmsSearch->addDocument($fields, $id);
                }
            }

            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }

    public function deleteEvents ($events)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('Events'));
            
            if(is_array($events))
            {
                $where = $this->getAdapter()->quoteInto('eid IN(?)', $events);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('eid = ?', $events);
            }

            $this->delete($where);

            if(is_array($events))
            {
                foreach($events as $events_id)
                {
                    $cmsSearch->deleteDocument($events_id);
                }
            }
            else
            {
                $cmsSearch->deleteDocument($events);
            }
            
            $this->getAdapter()->commit();
        }
        catch (Cms_Exception $e)
        {
            $this->getAdapter()->rollBack();
        }
    }
   
}
?>
