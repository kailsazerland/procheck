<?php

class UsersController extends AppController {
    public $name = 'Users';   
    public $paginate = null;
    
    public function login()
    {
        $this->layout = 'bootstrap-layout';          
        
        if(isset($this->data['login'])) {
            $user = $this->User->find('first');
            if(empty($user)) {
                $user_id = $this->_save_user($this->data); //Создадим первого пользователя
            }
            $this->Auth->logout();  
            if($this->Auth->login()) {
                $this->redirect('/');
            }
            else if(isset($this->data['User']))    
                $this->Session->setFlash('Неверное имя пользователя или пароль!', 'default', array('class' => 'panel-danger'));
                $this->request->data['User']['password'] = null;
        }

    }
        
    public function logout()
    {
        $this->Auth->logout();
        $this->redirect('login');
    }
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'users','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'users','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['User'] =  array('limit' => 30
                                            ,'order' => array('created' => 'DESC'));   
        
        $users = $this->paginate('User');
        $this->set('users',$users);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('User' => array(
            'id' => 0
           ,'hash_pswd' => 0
           ,'Groups' => $this->User->Group->find('list')
        ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->User->id = $id;
        $data = $this->User->read(); 
        $data['User']['Groups'] = $this->User->Group->find('list');
        $this->request->data = $data;
        $this->ajaxRender();
    }

    private function _save_user($data = null)
    {
        if(!isset($data)) $data = $this->data['User'];
        if(!empty($data))
        {
            if($this->User->saveAll($data))
            { $this->Session->setFlash('Пользователь сохранен.', 'default', array('class' => 'panel-success'));
                return $this->User->getLastInsertid();
            } else $this->Session->setFlash('Ошибка сохранения пользователя!', 'default', array('class' => 'panel-danger')); 
        }
        return false;
    }
    
    public function save()
    {
        if(isset($this->data['User'])&&!isset($this->data['cancel']))
            $this->_save_user($this->data);
        $this->redirect('view');
    }    
    
    public function delete($id)
    {
        if($this->User->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }

    public function beforeFilter()
    {

    }
 

}	 

?>
