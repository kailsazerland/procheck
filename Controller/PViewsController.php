<?php

class PViewsController extends AppController {
    public $name = 'PViews';
    public $layout = 'maina-layout';
    public $paginate = null;

    public function group_view()
    {
        $this->ajaxRender();
    }

    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'p_views','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'p_views','action' => 'delete','class' => 'btn-danger confirm')
        ));

      $this->paginate['PView'] =  array('limit' => 30
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('id' => 'DESC'));

        $ppriority = $this->paginate('PView');
        $this->set('ppriority',$ppriority);

        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('PView' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->PView->id = $id;
        $data = $this->PView->read();
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['PView'])&&!isset($this->data['cancel']))
        {      
            if($this->PView->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->PView->delete($id))
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
                'PView.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }      

}	 

?>
