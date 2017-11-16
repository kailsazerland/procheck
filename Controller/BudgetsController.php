<?php

class BudgetsController extends AppController {
    public $name = 'Budgets';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    
    public function view_budget_in()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Просмотр','controller' => 'budgets','action' => 'analize_budget','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'budgets','action' => 'delete_budget','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Budget'] =  array('limit' => 12
                                            ,'conditions' => array_merge(array('parent_id' => 0, 'Budget.type_id' => $this->Budget->Type->get_id_in()),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        $budgets = $this->paginate('Budget');
       
        $this->set('budgets',$budgets);
    
        $this->ajaxRender('view_budget_in');
    }
    
    public function add_budget_in()
    {
        $this->request->data = array('Budget' => array('id' => 0, 'type_id' => $this->Budget->Type->get_id_in()));
        $this->ajaxRender('edit_budget_in'); 
        $this->render('edit_budget_in');
    }

    public function edit_budget_in($id = 0)
    {
        $this->Budget->id = $id;
        $data = $this->Budget->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    } 
    
    public function view_budget_out()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Просмотр','controller' => 'budgets','action' => 'analize_budget','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'budgets','action' => 'delete_budget','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Budget'] =  array('limit' => 12
                                            ,'conditions' => array_merge(array('parent_id' => 0, 'Budget.type_id' => $this->Budget->Type->get_id_out()),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        $budgets = $this->paginate('Budget');
       
        $this->set('budgets',$budgets);
    
        $this->ajaxRender('view_budget_out');
    }
    
    public function add_budget_out()
    {
        $this->request->data = array('Budget' => array('id' => 0, 'type_id' => $this->Budget->Type->get_id_out()));
        $this->ajaxRender('edit_budget_out'); 
        $this->render('edit_budget_out');
    }    

    public function edit_budget_out($id = 0)
    {
        $this->Budget->id = $id;
        $data = $this->Budget->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }     
    
    public function save()
    {
        if(isset($this->data['Budget'])&&!isset($this->data['cancel']))
        {        
            //$datestring = $this->data['Budget']['period']['year'] . '-' .$this->data['Budget']['period']['month'] . '-01';
            //$this->request->data['Budget']['period'] = $datestring;
            //$this->request->data['Budget']['period'] .= '-01';
            
            $period = new DateTime($this->data['Budget']['period']);            
            $this->request->data['Budget']['period'] = $period->format('Y-m-01 00:00:00');
            
            $status = $this->Budget->create_budget($this->data['Budget']['period'],$this->data['Budget']['type_id']);
            if($status == 0) $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            else if($status == 1) $this->Session->setFlash('План/факт анализ за данный период уже существует.', 'default', array('class' => 'panel-danger')); 
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));                              
        }
        if(isset($this->data['Budget']['type_id'])) $type = $this->data['Budget']['type_id']; else  $type = 0;
        if($this->Budget->Type->is_in($type))
            $this->redirect('view_budget_in');
        else if($this->Budget->Type->is_out($type))
            $this->redirect('view_budget_out');
        else $this->redirect('/');
    }
    
    public function delete_budget($id = 0)
    {
        $this->Budget->id = $id;
        $data = $this->Budget->read();        
        $this->delete($id);
        if(isset($data['Budget']['type_id'])) $type = $data['Budget']['type_id']; else  $type = 0;
        if($this->Budget->Type->is_in($type))
            $this->redirect('view_budget_in');
        else if($this->Budget->Type->is_out($type))
            $this->redirect('view_budget_out');
        else $this->redirect('/');
    }
    
    public function delete($id = 0)
    {
        if($this->Budget->delete($id)) {
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
            return true;
        }
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger')); 
        return false;
    }
    
    public function save_article_budget()
    {
        if(isset($this->data['BudgetValues'])&&!isset($this->data['cancel']))
        {
            $this->loadModel('CashFlow');
            //$article_budget = $this->Budget->BudgetValues->find('first',array('conditions' => array('article_id' => $this->data['BudgetValues']['article_id']
                                                                                                   //,'period' => $this->data['Budget']['period'])));
            //if(!isset($article_budget['BudgetValues'])) {
                $this->request->data['BudgetValues']['parent_id'] = $this->data['Budget']['id'];
                $this->request->data['BudgetValues']['period'] = $this->data['Budget']['period'];
                $this->request->data['BudgetValues']['type_id'] = $this->data['Budget']['type_id'];
                
                $this->request->data['BudgetValues']['cash_fact'] = $this->CashFlow->get_summ_to_article($this->data['BudgetValues']['article_id'],$this->data['Budget']['period'],$this->data['Budget']['type_id']);
                
                $this->request->data['BudgetValues']['difference'] = $this->data['BudgetValues']['cash_plan'] - $this->data['BudgetValues']['cash_fact'];
                
                if($this->Budget->BudgetValues->save($this->data['BudgetValues']))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
                else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));        
            //} else $this->Session->setFlash('Статья за данный период уже добавлена!', 'default', array('class' => 'panel-danger'));  
                
            $this->Budget->recount($this->data['Budget']['id']);
        }
        $this->redirect('analize_budget/' . $this->data['Budget']['id']);
    }
    
    public function delete_article_budget($id = 0)
    {
        $this->Budget->BudgetValues->id = $id;
        $data = $this->Budget->BudgetValues->read();        
        $this->delete($id);
        $this->Budget->recount($data['BudgetValues']['parent_id']);
        if(isset($data['BudgetValues']['parent_id'])&&$data['BudgetValues']['parent_id']!=0)
            $this->redirect('analize_budget/' . $data['BudgetValues']['parent_id']);
        else $this->redirect('/');
    }
    
    public function analize_budget($id = 0)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'budgets','action' => 'edit_article','class' => 'btn-primary dialog-link')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'budgets','action' => 'delete_article_budget','class' => 'btn-danger confirm')
        )); 
                                
        $this->Budget->id = $id;
        $data = $this->Budget->read(); 
        $this->set('budgets',$data);

        $this->paginate['BudgetValues'] =  array('limit' => 30
                                    ,'conditions' => array('BudgetValues.parent_id' => $id)
                                    ,'order' => array('created' => 'DESC'));
        $this->set('budget_values',$this->paginate('BudgetValues'));        
        
        if($this->Budget->Type->is_in($data['Budget']['type_id']))
            $this->set('view_budget',array('name' => 'Бюджет доходов','view' => 'view_budget_in'));
        else if($this->Budget->Type->is_out($data['Budget']['type_id']))
            $this->set('view_budget',array('name' => 'Бюджет расходов','view' => 'view_budget_out'));
        
        $this->ajaxRender();
    }

    
    public function add_article($id = 0) 
    {  
        $this->Budget->id = $id;
        $data = $this->Budget->read(); 
        $this->request->data = array('Budget' => $data['Budget'], 'BudgetValues' => array('id' => 0, 'cash_plan' => 0, 'cash_fact' => 0));
        //$this->Budget->Article->Behaviors->attach('Containable');
        //$articles = $this->Budget->Article->find('list',array('contain' => array('Type' => array('conditions' => array('Type.alias' => 'in'))),'conditions' => array('not' => array('Type.id' => null))));
        $articles = $this->Budget->Article->find('list',array('conditions' => array('Article.type_id' => $data['Budget']['type_id'])));
        $this->set('articles',$articles);
        
        $this->ajaxRender('edit_article');
        $this->render('edit_article');
    }    
    
    public function edit_article($id = 0) 
    {      
        $this->Budget->BudgetValues->id = $id;
        $data = $this->Budget->BudgetValues->read(); 
        $this->request->data = $data;    
        
        $articles = $this->Budget->Article->find('list',array('conditions' => array('Article.type_id' => $data['Budget']['type_id'])));
        $this->set('articles',$articles);
        
        $this->ajaxRender();
    }
    
    public function save_change_budget() 
    {      
        $this->layout = 'ajax';
        $this->autoRender = false; 
     
        
        $this->Budget->BudgetValues->id = $this->data['id'];
        $budget_value = $this->Budget->BudgetValues->read();
        
        $this->Budget->id = $budget_value['BudgetValues']['parent_id'];
        $budget = $this->Budget->read();
        
        $cash_plan = $cash_fact = 0;
        if($this->data['column'] == 'cash_plan')
            $cash_plan = $this->data['value'];
        else $cash_plan = $budget_value['BudgetValues']['cash_plan'] ;
        if($this->data['column'] == 'cash_fact')
            $cash_fact = $this->data['value'];
        else $cash_fact = $budget_value['BudgetValues']['cash_fact'] ;        
        
        $difference = $cash_plan - $cash_fact;
        
        $this->Budget->BudgetValues->updateAll(array('cash_plan' => $cash_plan, 'cash_fact' => $cash_fact, 'difference' => $difference), array('BudgetValues.id' => $this->data['id']));
        $this->Budget->recount();
        
        return json_encode(array('cash' => $this->data['value'], 'difference' => $difference));
    }
    
    public function copy_budget_in() 
    {
        $data = $this->Budget->find('first',array(/*'fields' => array('Budget.id','created')
                                                 ,*/'conditions' => array('Budget.parent_id' => 0
                                                                       ,'Budget.type_id' => $this->Budget->Type->get_id_in())
                                                 ,'order' => array('Budget.created' => 'DESC')
        ));  
        if(empty($data)) $this->Session->setFlash('Нет предыдущих периодов!', 'default', array('class' => 'panel-danger'));
        else {
            $this->Budget->copy($data);
            $this->Session->setFlash('Выполнено успешно.', 'default', array('class' => 'panel-success')); 
        }
        $this->redirect('view_budget_in');     
    }
    
    public function copy_budget_out() 
    {
        $data = $this->Budget->find('first',array(/*'fields' => array('Budget.id','created')
                                                 ,*/'conditions' => array('Budget.parent_id' => 0
                                                                       ,'Budget.type_id' => $this->Budget->Type->get_id_out())
                                                 ,'order' => array('Budget.created' => 'DESC')
        ));  
        if(empty($data)) $this->Session->setFlash('Нет предыдущих периодов!', 'default', array('class' => 'panel-danger'));
        else {
            $this->Budget->copy($data);
            $this->Session->setFlash('Выполнено успешно.', 'default', array('class' => 'panel-success')); 
        }
        $this->redirect('view_budget_out');       
    }    
    
    public function export($id = 0)
    {
        $this->Budget->id = $id;
        $data = $this->Budget->read(); 
        App::uses('TimeHelper', 'View/Helper');
        $helper_time = new TimeHelper(new View());         
        if($this->Budget->Type->is_in($data['Budget']['type_id']))            
            $name = 'Бюджет_доходов_';
        else $name = 'Бюджет_расходов_';    
        $name .= __d('cake',$helper_time->format('F',$data['Budget']['period'])) . '_' . $helper_time->format('Y',$data['Budget']['period']);
        $this->Budget->set_params(array('period' => $helper_time->format('d.m.Y',$data['Budget']['period'])));
        $this->Budget->export($name,array('parent_id' => $id));
    }

    public function beforeFilter()
    {

    }
    
    public function get_search_conditions($search_text = null) { 
        $conditions = array(
            'OR' => array(                
                'DATE_FORMAT(Budget.period, "%m.%Y") LIKE "%' . $search_text . '%"'
            )            
        );
        return $conditions;
    }

}	 

?>
