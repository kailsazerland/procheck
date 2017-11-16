<?php

class ContragentsController extends AppController {
    public $name = 'Contragents';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function group_view()
    {
        $groups = $this->Contragent->OrgsGroup->find('all',array('conditions' => array('OR' => array(array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_in()),array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_out())))));
        $this->set('groups',$groups);
        
        $this->ajaxRender();
    }    
    
    public function view($group_id)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Счета','controller' => 'accounts','action' => 'view','class' => 'btn-info')
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'contragents','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'contragents','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Contragent'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('orgs_group_id' => $group_id),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        
        $contragents = $this->paginate('Contragent');
        $this->set('contragents',$contragents);
        
        $group = $this->Contragent->OrgsGroup->find('first',array('conditions' => array('OrgsGroup.id' => $group_id)));
        $this->set('group',$group);
    
        $this->ajaxRender('view');
    }

    public function add()
    {        
        $this->request->data = array('Contragent' => array(
            'id' => 0
           ,'OrgsGroup' => $this->Contragent->OrgsGroup->find('list',array('conditions' => array('OR' => array(array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_in())
                                                                                                                              ,array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_out())))))
           //,'Types' => $this->Contragent->Type->get_list()
            ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Contragent->id = $id;
        $data = $this->Contragent->read(); 
        //$data['Contragent']['Types'] = $this->Article->Type->get_list();
        $data['Contragent']['OrgsGroup'] = $this->Contragent->OrgsGroup->find('list',array('conditions' => array('OR' => array(array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_in())
                                                                                                                              ,array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_out())))));
        $this->request->data = $data;
        $this->ajaxRender();
    }

    /*
     * Окно редактора в диалоговом окне
     */
    public function edit_dialog($id = 0)
    {
        $this->Contragent->id = $id;
        $data = $this->Contragent->read(); 
        //$data['Contragent']['Types'] = $this->Article->Type->get_list();
        $data['Contragent']['OrgsGroup'] = $this->Contragent->OrgsGroup->find('list',array('conditions' => array('OR' => array(array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_in())
                                                                                                                              ,array('type_id' => $this->Contragent->OrgsGroup->Type->get_id_out())))));
        $this->request->data = $data;        
        $this->ajaxRender();
    }

    public function save()
    {
        if(isset($this->data['Contragent'])&&!isset($this->data['cancel']))
        {
            if($this->Contragent->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/' . $this->data['Contragent']['orgs_group_id']);
    }
    
    /*
     * Сохранение из диалогового окна
     */
    public function save_dialog()
    {   
        $id = $this->data['Contragent']['id'];
        if(isset($this->data['Contragent'])&&!isset($this->data['cancel']))
        {
            if($this->Contragent->saveAll($this->data)) {
                if($id == 0)
                    $id = $this->Contragent->getLastInsertID();
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            }
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }        
        $this->ajaxRender('bodyNotModify', null, array('action' => 'changeeditform', 'target' => 'form', 'param' => $id));
    } 

    public function delete($id = 0)
    {
        if($this->Contragent->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('group_view');
    }    
    
    public function sync()
    {
        set_time_limit(300);
        $this->loadModel('ContragentSync');
        $syncdata = $this->ContragentSync->query("select 
                                                sel.CustomerNumber,
                                                max(sel.name) as name,
                                                max(sel.grp_id) as grp_id,
                                                max(case sel.num when  1 then sel.value end) as bank,
                                                max(case sel.num when  2 then sel.value end) as inn,
                                                max(case sel.num when  3 then sel.value end) as account
                                            from
                                            (select @row_number:=CASE
                                                WHEN @customer_no = sel_in.id THEN @row_number + 1
                                                ELSE 1
                                            END AS num,
                                            @customer_no:=sel_in.id as CustomerNumber,
                                            sel_in.*
                                            from (
                                                    select ifnull(sel.id,a.id)as id,ifnull(sel.name,a.name)as name,ifnull(sel.grp_id,a.agent_group_id)as grp_id, ifnull(sel.value,'') as value
                                                    from agents a left outer join(
                                                    select a.id,a.name,a.agent_group_id as grp_id, tv.value
                                                    from agents a, tags_values tv, tags t
                                                    where tv.agent_id = a.id
                                                    and t.id = tv.tag_id
                                                    and t.alias in ('{inn}','{bank}','{rs}')
                                                    order by t.alias, a.id)sel on (a.id = sel.id)
                                            ) sel_in, (SELECT @customer_no:=0,@row_number:=0) as r
                                            ORDER BY customerNumber) sel
                                            group by sel.CustomerNumber"
        );
   
        $data = array();
        $count = 0;
        foreach ($syncdata as $value) {
            $contragent = $this->Contragent->find('first',array('conditions' => array('Contragent.name' => $value[0]['name'])));
            if(empty($contragent)) {
                if($value[0]['grp_id']==6)$grp_id = 6; else $grp_id = 4;
                $data = array(
                    'Contragent' => array(
                        'id' => 0,
                        'name' => $value[0]['name'],
                        'inn' => $value[0]['inn'],
                        'orgs_group_id' => $grp_id
                    ),
                    'Account' => array(
                        'Account' => array(
                            'bank' => $value[0]['bank'],
                            'account' => $value[0]['account']
                        )
                    )
                );
                if($this->Contragent->saveAll($data))
                    $count++;
            }
        }
        
        $this->Session->setFlash('Синхронизированно ' . $count . ' новых записей.', 'default', array('class' => 'panel-success'));  
        $this->redirect('group_view');
    }

    public function beforeFilter()
    {

    }
    
    public function get_search_conditions($search_text = null) {
        $orgs_groups = $this->Contragent->OrgsGroup->find('list',array('fields' => array('id') ,'conditions' => array('OrgsGroup.name LIKE "%' . $search_text . '%"')));
        $conditions = array(
            'OR' => array(
                'Contragent.name LIKE "%' . $search_text . '%"'
               ,'Contragent.orgs_group_id' => $orgs_groups
               ,'Contragent.inn LIKE "%' . $search_text . '%"'
               ,'Contragent.account LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }

}	 

?>
