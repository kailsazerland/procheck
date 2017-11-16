<?php

class NalogsController extends AppController {
    public $name = 'Nalogs';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'paytypes','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'paytypes','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Nalog'] =  array('limit' => 30
                                         ,'conditions' => $this->search_conditions
                                         ,'order' => array('created' => 'DESC'));   
        
        $nalogs= $this->paginate('Nalog');
        $this->set('nalogs',$nalogs);
    
        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Nalog' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Nalog->id = $id;
        $data = $this->Nalog->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Nalog'])&&!isset($this->data['cancel']))
        {      
            if($this->Nalog->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->Nalog->delete($id))
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
                'Nalog.name LIKE "%' . $search_text . '%"'
               ,'Nalog.percent LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }       

}	 

?>
