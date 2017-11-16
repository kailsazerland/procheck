<?php

class PermissionsController extends AppController {
    public $name = 'Permissions';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'permissions','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'permissions','action' => 'delete','class' => 'btn-danger confirm')
        ));
        
        $this->paginate['Permission'] =  array('limit' => 30
                                            ,'order' => array('created' => 'DESC'));
        
        $permission = $this->paginate('Permission');
        $this->set('permission',$permission);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('Permission' => array(
            'id' => 0
           ,'action' => 'view'
           ,'Permissions' => array_merge(array(0 => 'нет'),$this->Permission->find('list'))
        ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Permission->id = $id;
        $data = $this->Permission->read(); 
        $data['Permission']['Permissions'] = array_merge(array(0 => 'нет'),$this->Permission->find('list'));
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Permission'])&&!isset($this->data['cancel']))
        {
//var_dump($this->data);die();
            if($this->Permission->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));
        }
        $this->redirect('view');
    }

    public function delete($id = 0)
    {
        if($this->Permission->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }

    public function beforeFilter()
    {

    }

}	 

?>
