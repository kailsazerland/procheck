<?php

class GroupsController extends AppController {
    public $name = 'Groups';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'groups','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'groups','action' => 'delete','class' => 'btn-danger confirm')
        ));
        
        $this->paginate['Group'] =  array('limit' => 30
                                            ,'order' => array('created' => 'DESC'));
        
        $groups = $this->paginate('Group');
        $this->set('groups',$groups);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('Group' => array(
            'id' => 0
           ,'Permissions' => $this->Group->Permission->find('list')
        ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Group->id = $id;
        $data = $this->Group->read(); 
        $data['Group']['Permissions'] = $this->Group->Permission->find('list');
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Group'])&&!isset($this->data['cancel']))
        {
            if($this->Group->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));
        }
        $this->redirect('view');
    }

    public function delete($id = 0)
    {
        if($this->Group->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }

    public function beforeFilter()
    {

    }

}	 

?>
