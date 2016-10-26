<?php
class Models_News extends Cms_Db
{
    protected $_name = 'news';
    protected $_primary = 'nid';

    public function getNewses ($options = array(), $language = LOCALE_ID)
    {
        $usersModels = new Models_Users;
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('n' => $this->_name))
                       ->joinLeft(array('nt' => 'news_translation'), 'nt.nid = n.nid AND nt.lid = "'.$language.'"', array('title' => 'nt.title', 'attachments' => 'nt.attachments', 'content' => 'nt.content'))
                       ->group('n.nid');

        if(isset($options) && is_array($options))
        {
            if(isset($options['nid']))
            {
                if(is_array($options['nid']))
                {
                    $select->where('n.nid IN (?)', $options['nid']);
                }
            }
            
            if(isset($options['limit']))
            {
                $select->limit($options['limit']);
            }
            
            if(isset($options['order']))
            {
                $select->order('n.'.$options['order']);
            }
            
            if(isset($options['type']))
            {
                $select->where('n.type = ?', $options['type']);
            }
        }
        
        $results = $this->fetchAll($select);

        $newses = array();
        if($results)
        {
            foreach($results as $result)
            {
                $user = $usersModels->getUser(array('uid' => $result['uid']));
                
                $newses[$result['nid']] = array(
                    'nid'         =>   $result['nid'],
                    'uid'         =>   $result['uid'],
                    'user'        =>   $user,
                    'type'        =>   $result['type'],
                    'created_at'  =>   $result['created_at'],
                    'translation' =>   array(
                        'title'       =>   $result['title'],
                        'attachments' =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                        'content'     =>   $result['content'],
                    )
                );
            }
        }

        return $newses;
    }
    
    public function getNews ($options, $language = LOCALE_ID)
    {
        $usersModels = new Models_Users;
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('n' => $this->_name))
                       ->joinLeft(array('nt' => 'news_translation'), 'nt.nid = n.nid AND nt.lid = "'.$language.'"', array('title' => 'nt.title','attachments' => 'nt.attachments','content' => 'nt.content'));

        if(isset($options) && is_array($options))
        {
            if(isset($options['nid']))
            {
                $select->where('n.nid = ?', $options['nid']);
            }
        }

        $result = $this->fetchRow($select);

        $news = array();
        if($result)
        {
            $result = $result->toArray();

            $user = $usersModels->getUser(array('uid' => $result['uid']));
            
            $news = array(
                'nid'         =>   $result['nid'],
                'uid'         =>   $result['uid'],
                'type'        =>   $result['type'],
                'user'        =>   $user,
                'created_at'  =>   $result['created_at'],
                'translation' =>   array(
                    'title'       =>   $result['title'],
                    'attachments' =>   ($result['attachments'] != null ? json_decode($result['attachments'], true) : null),
                    'content'     =>   $result['content'],
                )
            );
        }

        return $news;
    }

    public function addNews ($formData)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $newsData = array(
                'uid'         =>  $formData['uid'],
                'created_at'  =>  new Zend_Db_Expr('NOW()'),
                'type'        =>  ($formData['type'] ? $formData['type'] : 0)
            );

            $last_id = $this->insert($newsData);

            $usersModels = new Models_Users;
            $user = $usersModels->getUser(array('uid' => $newsData['uid']));
            
            $newsTranslationModels = new Models_NewsTranslation;
            $languagesModels       = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('News'));

            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $newsTranslationData = $formData['languages'][$language['code']];
                    $newsTranslationData['nid']  = $last_id;
                    $newsTranslationData['lid']  = $language['lid'];

                    $newsTranslationModels->insert($newsTranslationData);             

                    $fields = array(
                        'created_at' =>   date('d-m-Y H:i:s'),   
                        'author'     =>   $user['firstname'].' <'.$user['username'].'> '.$user['lastname'],   
                        'title'      =>   $newsTranslationData['title'],   
                        'content'    =>   $newsTranslationData['content']
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
    

    public function editNews ($formData, $id)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $newsData = array(
                'type'  =>  ($formData['type'] ? $formData['type'] : 0)
            );
            
            $where = $this->getAdapter()->quoteInto('nid = ?', $id);
            $this->update($newsData, $where);

            $news = $this->getNews(array('nid' => $id));
            
            $usersModels = new Models_Users;
            $user = $usersModels->getUser(array('uid' => $news['uid']));
            
            
            // Zapisywanie translacji

            $newsTranslationModels = new Models_NewsTranslation;
            $languagesModels       = new Models_Languages;
            
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('News'));
            $cmsSearch->deleteDocument($id);
            
            $languages = $languagesModels->getLanguages();
            if($languages)
            {
                foreach($languages as $language)
                {
                    $newsTranslationData = $formData['languages'][$language['code']];

                    $where = array(
                        $this->getAdapter()->quoteInto('nid = ?', $id),
                        $this->getAdapter()->quoteInto('lid = ?', $language['lid'])
                    );

                    if($newsTranslationModels->fetchRow($where))
                    {
                        $newsTranslationModels->update($newsTranslationData, $where);
                    }
                    else
                    {
                        $data['lid']  = $language['lid'];
                        $data['nid']  = $id;
                        $newsTranslationModels->insert($data);
                    }           

                    $fields = array(
                        'created_at' =>   $news['created_at'],   
                        'author'     =>   $user['firstname'].' <'.$user['username'].'> '.$user['lastname'],   
                        'title'      =>   $newsTranslationData['title'],   
                        'content'    =>   $newsTranslationData['content']
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

    public function deleteNews ($news)
    {
        $this->getAdapter()->beginTransaction();
        try
        {
            $cmsSearch = new Cms_Search;
            $cmsSearch->setIndex(Cms_Search_Index::getIndex('News'));
            
            if(is_array($news))
            {
                $where = $this->getAdapter()->quoteInto('nid IN(?)', $news);
            }
            else
            {
                $where = $this->getAdapter()->quoteInto('nid = ?', $news);
            }

            $this->delete($where);

            if(is_array($news))
            {
                foreach($news as $news_id)
                {
                    $cmsSearch->deleteDocument($news_id);
                }
            }
            else
            {
                $cmsSearch->deleteDocument($news);
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
