<?php

class ClassificatorsController extends AppController {
    public $name = 'Classificators';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view($type_id = 2)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'classificators','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'classificators','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Classificator'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('type_id' => $type_id),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        
        $classificators = $this->paginate('Classificator');
        $this->set('classificators',$classificators);
    
        $this->ajaxRender('view');
        $this->render('view');
    }

    public function add()
    {
        $this->request->data = array('Classificator' => array('type_id' => 2));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Classificator->id = $id;
        $data = $this->Classificator->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }         

    public function save()
    {
        if(isset($this->data['Classificator'])&&!isset($this->data['cancel']))
        {
            if($this->Classificator->saveAll($this->data)) {              
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            }
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/2');
    }    

    public function delete($id = 0)
    {
        if($this->Classificator->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view/2');
    }    

    public function beforeFilter()
    {

    }
    
    public function get_search_conditions($search_text = null) {        
        $conditions = array(

        );
        return $conditions;
    }  

}	 

?>
