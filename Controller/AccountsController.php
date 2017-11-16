<?php

class AccountsController extends AppController {
    public $name = 'Accounts';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view($org_id = 0)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'accounts','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'accounts','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Account'] =  array('limit' => 30
                                           ,'conditions' => array('org_id' => $org_id)
                                           ,'order' => array('created' => 'DESC'));   
        
        $accounts = $this->paginate('Account');
        $this->set('accounts',$accounts);
        
        $org = $this->Account->Org->read(false,$org_id);
        $this->set('org',$org);   
        
        if($org['Org']['orgs_group_id'] == 99) $view_back = '/orgs/view';
        else $view_back = '/contragents/view/' . $org['Org']['orgs_group_id'];
        $this->set('view_back',$view_back);
        
    
        $this->ajaxRender('view');
    }

    public function add($org_id = 0)
    {
        $this->request->data = array('Account' => array('id' => 0, 'org_id' => $org_id));
        
        $this->set('org',$this->Account->Org->read(false,$org_id));
        
        $this->ajaxRender('edit');                 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Account->id = $id;
        $data = $this->Account->read(); 
        $this->request->data = $data;
        
        $this->set('org',$this->Account->Org->read(false,$data['Account']['org_id']));
        
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Account'])&&!isset($this->data['cancel']))
        {      
            if($this->Account->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/' . $this->data['Account']['org_id']);
    }    

    public function delete($id = 0)
    {
        $this->Account->id = $id;
        $data = $this->Account->read();         
        if($this->Account->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view/' . $data['Account']['org_id']);
    }    

    public function beforeFilter()
    {

    }    

}	 

?>
