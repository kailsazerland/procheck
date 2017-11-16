<?php

class SourcesController extends AppController {
    public $name = 'Sources';
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'sources','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'sources','action' => 'delete','class' => 'btn-danger confirm')
        ));

        $this->paginate['Source'] =  array('limit' => 30
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('created' => 'DESC'));

        $sources = $this->paginate('Source');
        $this->set('sources',$sources);

        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Source' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Source->id = $id;
        $data = $this->Source->read();
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Source'])&&!isset($this->data['cancel']))
        {      
            if($this->Source->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->Source->delete($id))
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
                'Source.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }      

}	 

?>
