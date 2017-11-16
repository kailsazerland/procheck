<?php

class OrdersController extends AppController {
    public $name = 'Orders';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
	public function print_arr($array)
    {
		echo "<pre>" . print_r($array) . "</pre>";
	}
	
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Карточка','controller' => 'orders','action' => 'view_card','class' => 'btn-success')
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'orders','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'orders','action' => 'delete','class' => 'btn-danger confirm')
        ));
        $this->paginate['Order'] =  array('limit' => 30
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('created' => 'DESC'));   
        
        $orders = $this->paginate('Order');
        $this->set('orders',$orders);                
    
        $this->ajaxRender('view');
    }
	
    public function neop()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Отправить на утверждение','icon' => 'fa fa-paper-plane', 'controller' => 'orders','action' => 'send_elem','class' => 'btn-success')
           ,2 => array('name' => 'Утвердить','icon' => 'fa fa-thumbs-up','controller' => 'orders','action' => 'approve','class' => 'btn-primary')
           ,3 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'orders','action' => 'edit','class' => 'btn-primary')
           ,4 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'orders','action' => 'delete','class' => 'btn-danger confirm')
           ,5 => array('name' => 'Платеж','icon' => 'fa fa-credit-card','controller' => 'orders','action' => 'pay','class' => 'btn-primary')
           ,6 => array('name' => 'Оплачено','icon' => 'fa fa-money','controller' => 'orders','action' => 'check_money','class' => 'btn-primary')
        ));
//        $au = $this->Order->Aunpaid->find('list');
	
        $this->paginate['Aunpaid'] =  array('limit' => 30
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('id' => 'DESC'));

        $orders = $this->paginate('Aunpaid');
        $this->set('orders', $orders);
    
        $this->ajaxRender('neop');
    }

	 public function add_neop()
    {
        $this->request->data = array('Order' => array('id' => 0
                                                     ,'cash' => 0
                                                     ,'user_id' => $this->Auth->user('id')
                                                     ,'is_confrim' => 0
                                                     ,'Otdels' => $this->Order->Otdel->find('list')));
													 
		$organiz = $this->Order->OrderCard->Contragent->find('list',array('conditions' => 
			array('orgs_group_id' => '99')));
        $this->set('organiz',$organiz);    
		
		$contragents = $this->Order->OrderCard->Contragent->find('list',array('conditions' => 
            array('orgs_group_id' => $this->Order->OrderCard->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('alias' => 'provider'))))));
        $this->set('contragents',$contragents);  
		
		$pay_types = $this->Order->OrderCard->PayType->find('list');
        $this->set('pay_types',$pay_types);
		
		$stati = $this->Order->OrderCard->Article->find('list');
        $this->set('stati',$stati);
		
		$classif = $this->Order->OrderCard->query("SELECT id, name FROM classificators");
		
		$clas = array();
		foreach($classif as $key => $item){
			$clas[$item['classificators']["id"]] = $item['classificators']["name"];
		}
        $this->set('classif',$clas);
		
		$istok = $this->Order->OrderCard->Article->find('list',array('conditions' => array('type_id' => '2')));
        $this->set('istok',$istok);
		
		$auth = $this->Auth->user('id');
        $this->set('auth',$auth);
		
        $this->ajaxRender('edit_neop'); 
        $this->render('edit_neop');
    }  
	
    public function save_neop()
    {
    
        $this->redirect('view_neop');
    }
	
    public function add()
    {
        $this->request->data = array('Order' => array('id' => 0
                                                     ,'cash' => 0
                                                     ,'user_id' => $this->Auth->user('id')
                                                     ,'is_confrim' => 0
                                                     ,'Otdels' => $this->Order->Otdel->find('list')));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Order->id = $id;
        $data = $this->Order->read(); 
        $data['Order']['Otdels'] = $this->Order->Otdel->find('list');
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Order'])&&!isset($this->data['cancel']))
        {
            $period = new DateTime($this->data['Order']['period']);            
            $this->request->data['Order']['period'] = $period->format('Y-m-01 00:00:00');
            if($this->Order->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }  
    
    public function confirm($order_id)
    {
        $data = array('Order' => array(
            'id' => $order_id,
            'is_confrim' => true
        ));
        if($this->Order->saveAll($data)) {

            $this->Order->id = $data['Order']['id'];
            $order = $this->Order->read(); 
            $this->loadModel('Budget');
            $this->Budget->create_budget($order['Order']['period'],$this->Budget->Type->get_id_out());
            foreach ($order['OrderCard'] as $value) {
                
                $budget_val = $this->Budget->BudgetValues->find('first',array('conditions' => array('BudgetValues.parent_id' => $this->Budget->get_create_id(),'BudgetValues.article_id' => $value['article_id'])));
                if((isset($budget_val['BudgetValues']))) {
                    $budget_value = array(
                        'id' => $budget_val['BudgetValues']['id'],
                        'cash_plan' => $value['cash'] + $budget_val['BudgetValues']['cash_plan'],
                    );
                } else {
                    $budget_value = array(
                        'id' => 0,
                        'parent_id' => $this->Budget->get_create_id(),
                        'article_id' => $value['article_id'],
                        'period' => $order['Order']['period'],
                        'cash_plan' => $value['cash'],
                        'type_id' => $this->Budget->Type->get_id_out()
                    );
                }
                $this->Budget->BudgetValues->save($budget_value);
                $budget_val_id = $this->Budget->BudgetValues->getLastInsertid();
                if(!isset($budget_val_id))
                    $budget_val_id = $budget_val['BudgetValues']['id'];
                $this->Order->OrderCard->updateAll(array('budget_id' => $budget_val_id), array('OrderCard.id' => $value['id']));
            }
            $this->Budget->recount();

            $this->Session->setFlash('Заявка утверждена.', 'default', array('class' => 'panel-success'));                
        }
        else $this->Session->setFlash('Ошибка утверждения заявки!', 'default', array('class' => 'panel-danger')); 
        $this->redirect('view_card/' . $data['Order']['id']);
    }
    
    public function delete_confirm($order_id)
    {
        $data = array('Order' => array(
            'id' => $order_id,
            'is_confrim' => 0//false
        ));
        if($this->Order->saveAll($data)) {
            $this->loadModel('Budget');
            $order = $this->Order->read(false,$order_id);
            $budget_id = current($order['OrderCard']); 
            $budget = $this->Budget->read(false, $budget_id['budget_id']);
            if(!empty($budget)) {
                foreach ($order['OrderCard'] as $card) {      
                $budget_val = $this->Budget->BudgetValues->find('first',array('conditions' => array('BudgetValues.parent_id' => $budget['Budget']['parent_id'],'BudgetValues.article_id' => $card['article_id'])));
                    if((isset($budget_val['BudgetValues']))) {
                        $budget_value = array(
                            'id' => $budget_val['BudgetValues']['id'],
                            'cash_plan' => $budget_val['BudgetValues']['cash_plan'] - $card['cash']
                        );
                        $this->Budget->BudgetValues->save($budget_value);
                    } 
                    //$this->Budget->delete($card['budget_id']);
                }
                $this->Budget->recount($budget['Budget']['parent_id']);
            }
            $this->Session->setFlash('Утверждение по заявке снято.', 'default', array('class' => 'panel-success'));                
        }
        else $this->Session->setFlash('Ошибка снятия утверждения!', 'default', array('class' => 'panel-danger')); 
        $this->redirect('view_card/' . $data['Order']['id']);        
        
    }    

    public function delete($id = 0)
    {
        if($this->Order->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }
    
    
    public function add_card($order_id = 0)
    {
        
        $this->request->data = array('OrderCard' => array('id' => 0, 'order_id' => $order_id, 'cash' => 0),'Order' => array('id' => $order_id));
        
        $articles = $this->Order->OrderCard->Article->find('list',array('conditions' => array('Article.type_id' => $this->Order->OrderCard->Article->Type->get_id_out())));
        $this->set('articles',$articles);
        
        $contragents = $this->Order->OrderCard->Contragent->find('list',array('conditions' => 
            array('orgs_group_id' => $this->Order->OrderCard->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('alias' => 'provider'))))));
        $this->set('contragents',$contragents);        
        
        $pay_types = $this->Order->OrderCard->PayType->find('list');
        $this->set('pay_types',$pay_types);
        
        $nalogs = $this->Order->OrderCard->Nalog->find('list');
        $this->set('nalogs',$nalogs);                
        
        $this->ajaxRender('edit_card');
        $this->render('edit_card');
    }
    
    public function edit_card($id = 0)
    {
        $this->Order->OrderCard->id = $id;
        $data = $this->Order->OrderCard->read();      
        $this->request->data = $data;        
        
        $articles = $this->Order->OrderCard->Article->find('list',array('conditions' => array('Article.type_id' => $this->Order->OrderCard->Article->Type->get_id_out())));
        $this->set('articles',$articles);
        
        $contragents = $this->Order->OrderCard->Contragent->find('list',array('conditions' => 
            array('orgs_group_id' => $this->Order->OrderCard->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('alias' => 'provider'))))));
        $this->set('contragents',$contragents);        
        
        $pay_types = $this->Order->OrderCard->PayType->find('list');
        $this->set('pay_types',$pay_types);
        
        $nalogs = $this->Order->OrderCard->Nalog->find('list');
        $this->set('nalogs',$nalogs);        
        
        $this->ajaxRender();
    }    

    public function view_card($order_id = 0)
    {
        $this->set_action_menu( array(
           1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'orders','action' => 'edit_card','class' => 'btn-primary dialog-link')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'orders','action' => 'delete_card','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['OrderCard'] =  array('limit' => 30
                                            ,'conditions' => array('order_id' => $order_id)
                                            ,'order' => array('created' => 'DESC'));   
        
        $cards = $this->paginate('OrderCard');
        $this->set('cards',$cards);
        
        $this->request->data = $this->Order->read(null,$order_id);
        
        $this->set('confirm_permitted',$this->checkPermission('orders','confirm')&&$this->checkPermission('orders','delete_confirm'));
    
        $this->ajaxRender('view_card');
    }    

    public function save_card()
    {
        if(isset($this->data['OrderCard'])&&!isset($this->data['cancel']))
        {
            $this->Order->id = $this->data['OrderCard']['order_id'];
            $order = $this->Order->read(); 
            if($order['Order']['is_confrim'] == 0)            
            {
                if($this->validate_data('OrderCard',$this->data)&&$this->Order->OrderCard->saveAll($this->data,array('validate' => false))) {
                    $this->Order->recount($this->data['OrderCard']['order_id']);
                    $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
                }
            } else $this->Session->setFlash('Ошибка сохранения - заявка уже утверждена!', 'default', array('class' => 'panel-danger')); 
            $this->redirect('view_card/' . $this->data['OrderCard']['order_id']);
        }
        $this->redirect('view');
    }

    public function delete_card($id = 0)
    {
        $this->Order->OrderCard->id = $id;
        $order_card = $this->Order->OrderCard->read(); 
        if($order_card['Order']['is_confrim'] == 0)            
        {
            if($this->Order->OrderCard->delete($id)) {
                $this->Order->recount($order_card['OrderCard']['order_id']);
                $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
            }
            else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        } else $this->Session->setFlash('Ошибка удаления - заявка уже утверждена!', 'default', array('class' => 'panel-danger'));  
        $this->redirect('view_card/' . $order_card['Order']['id']);
    }
    
    public function export($id = 0)
    {
        $this->Order->id = $id;
        $data = $this->Order->read(); 
        App::uses('TimeHelper', 'View/Helper');
        $helper_time = new TimeHelper(new View());         
        $name = 'Карточка_расхода_';
        $name .= __d('cake',$helper_time->format('F',$data['Order']['period'])) . '_' . $helper_time->format('Y',$data['Order']['period']);
        $this->Order->OrderCard->export($name,array('order_id' => $id));
    }    
    
    public function beforeFilter()
    {

    }
    
    public function get_search_conditions($search_text = null) { 
        $otdels = $this->Order->Otdel->find('list',array('fields' => array('id') ,'conditions' => array('Otdel.name LIKE "%' . $search_text . '%"')));
        $users = $this->Order->User->find('list',array('fields' => array('id') ,'conditions' => array('User.username LIKE "%' . $search_text . '%"')));
        $conditions = array(
            'OR' => array(                
                'DATE_FORMAT(Order.created, "%d.%m.%Y") LIKE "%' . $search_text . '%"'
               ,'DATE_FORMAT(Order.period, "%m.%Y") LIKE "%' . $search_text . '%"'
               ,'Order.otdel_id' => $otdels
               ,'Order.cash LIKE "%' . $search_text . '%"'
               ,'Order.user_id' => $users
            )            
        );
        return $conditions;
    }    

}	 

?>
