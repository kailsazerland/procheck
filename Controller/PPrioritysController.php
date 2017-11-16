<?php

class PPrioritysController extends AppController {
    public $name = 'PPrioritys';
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'p_prioritys','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'p_prioritys','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
      $this->paginate['PPriority'] =  array('limit' => 30
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('id' => 'DESC'));

        $ppriority = $this->paginate('PPriority');
        $this->set('ppriority',$ppriority);

        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('PPriority' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->PPriority->id = $id;
        $data = $this->PPriority->read();
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['PPriority'])&&!isset($this->data['cancel']))
        {      
            if($this->PPriority->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->PPriority->delete($id))
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
                'PPriority.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }      

}	 

?>
