<?php

class ArticlesController extends AppController {
    public $name = 'Articles';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function type_view()
    {
        $types = $this->Article->Type->find('all');
        $this->set('types',$types);
        
        $this->ajaxRender();
    }
    
    public function view($type_id)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'articles','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'articles','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Article'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('type_id' => $type_id),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        
        $articles = $this->paginate('Article');
        $this->set('articles',$articles);
    
        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Article' => array('id' => 0,'Types' => $this->Article->Type->find('list')));
        //$this->request->data['Article']['Contragents'] = array();
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }

    public function edit($id = 0)
    {
        $this->Article->id = $id;
        $data = $this->Article->read(); 
        $data['Article']['Types'] = $this->Article->Type->find('list');
        //$data['Article']['Contragents'] = $this->Article->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->Article->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $data['Article']['type_id']))))));        
        $this->request->data = $data;
        $this->ajaxRender();
    }
    
    /*public function change()
    {
        $this->request->data = $this->data;
        $this->request->data['Article']['Types'] = $this->Article->Type->get_list();
        //$this->request->data['Article']['Contragents'] = $this->Article->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->Article->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $this->data['Article']['type_id']))))));        
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }*/ 

    public function save()
    {
        if(isset($this->data['Article'])&&!isset($this->data['cancel']))
        {      
            if($this->Article->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/' . $this->data['Article']['type_id']);
    }    

    public function delete($id = 0)
    {
        if($this->Article->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('type_view');
    }    

    public function beforeFilter()
    {

    }

    public function get_search_conditions($search_text = null) {
        $types = $this->Article->Type->find('list',array('fields' => array('id') ,'conditions' => array('Type.name LIKE "%' . $search_text . '%"')));        
        $conditions = array(
            'OR' => array(
                'Article.name LIKE "%' . $search_text . '%"'
               ,'Article.type_id' => $types 
            )
        );
        return $conditions;
    }    
    
}	 

?>
