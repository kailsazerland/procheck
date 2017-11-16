<?php

class OrgsController extends AppController {
    public $name = 'Orgs';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Счета','controller' => 'accounts','action' => 'view','class' => 'btn-info')
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'orgs','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'orgs','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Org'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('orgs_group_id' => 99),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        
        $orgs = $this->paginate('Org');
        $this->set('orgs',$orgs);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('Org' => array('id' => 0, 'orgs_group_id' => 99));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Org->id = $id;
        $data = $this->Org->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Org'])&&!isset($this->data['cancel']))
        {      
            if($this->Org->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->Org->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }  

    public function beforeFilter()
    {

    }

    public function get_search_conditions($search_text = null) {
        $conditions = array(
            'OR' => array(
                'Org.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }    
    
}	 

?>
