<?php

class OtdelsController extends AppController {
    public $name = 'Otdels';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'otdels','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'otdels','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Otdel'] =  array('limit' => 30
                                         ,'conditions' => $this->search_conditions
                                         ,'order' => array('created' => 'DESC'));   
        
        $otdels= $this->paginate('Otdel');
        $this->set('otdels',$otdels);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('Otdel' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Otdel->id = $id;
        $data = $this->Otdel->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Otdel'])&&!isset($this->data['cancel']))
        {      
            if($this->Otdel->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->Otdel->delete($id))
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
                'Otdel.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }       

}	 

?>
