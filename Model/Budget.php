<?php 
class Budget extends AppModel 
{   
    public $field_list = array(
       //'id' => 'Уникальный идентификатор'
        'Type.name' => 'Тип'        
       ,'Article.name' => 'Статья'
       ,'cash_plan' => 'План руб.'
       ,'cash_fact' => 'Факт руб.'
       ,'difference' => 'Разница руб.'
       ,'period' => 'Период'
    );
    
    public $params = array(
    );
    
    //public $virtualFields = array('max_created' => 'MAX(Budget.created)');
    public $belongsTo = array('Article','Type');
    /*public $hasMany = array('BudgetValues' => array('className' => 'Budget'
                                                        ,'foreignKey'    => 'parent_id'
                                                        ,'dependent' => true
                                                        ,'order' => array('created ASC')
                                    ));*/
    
    public $hasMany = array('BudgetValues' => array('className' => 'BudgetValues'
                                                        ,'foreignKey'    => 'parent_id'
                                                        ,'dependent' => true
                                                        ,'order' => array('created ASC')
                                    ));    
    
    /*public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct();
        $this->virtualFields = array('max_created' => 'MAX(' . $this->name . '.created)');
    }*/
    
    public function create_budget($period = null, $type = null)
    {         
        if(isset($period)&&isset($period)) {
            $budget = $this->find('first',array('conditions' => array('parent_id' => 0, 'period' => $period, 'Budget.type_id' => $type)));        
            if(!isset($budget['Budget'])) {
                $budget['Budget'] = array(
                    'id' => 0,
                    'parent_id' => 0,
                    'period' => $period,
                    'type_id' => $type
                );
                if($this->saveAll($budget)) {
                    $this->id = $this->getLastInsertid();
                    return 0;
                }
                else return -1;       
            }
            else {
                $this->id = $budget['Budget']['id'];
                return 1;
            }
        }        
    }
    
    public function get_create_id()
    {
        return $this->id;
    }
    
    public function recount($id = null)
    {
        if(!isset($id)) $id = $this->id;
        $budget_values = $this->BudgetValues->find('all',array('fields' => array('id','cash_plan','cash_fact'),'conditions' => array('BudgetValues.parent_id' => $id)));

        foreach ($budget_values as $value)
            $this->BudgetValues->updateAll(array('difference' => $value['BudgetValues']['cash_plan'] - $value['BudgetValues']['cash_fact']
                               ), array('BudgetValues.id' => $value['BudgetValues']['id']));        
        $budget_values = array_reduce($budget_values, function ($v, $w) {
            if(!isset($v['cash_plan'])||!isset($v['cash_fact'])) {                
                $v['cash_plan'] = $v['cash_fact'] = 0;
            }
            $v['cash_plan'] += $w['BudgetValues']['cash_plan'];
            $v['cash_fact'] += $w['BudgetValues']['cash_fact'];
            return $v;        
        });
        $this->updateAll(array('cash_plan' => $budget_values['cash_plan']
                              ,'cash_fact' => $budget_values['cash_fact']
                              ,'difference' => $budget_values['cash_plan'] - $budget_values['cash_fact']
                               ), array('Budget.id' => $id));
        return true;
    }
    
    public function copy($data = null)
    {
        if(isset($data)){
            
            App::import('model','CashFlow');
            $this->CashFlow = new CashFlow();
            
            $data['Budget']['id'] = 0;
            $next_period = new DateTime($data['Budget']['period']);
            $next_period->modify('+1 month');
            $data['Budget']['period'] = $next_period->format('Y-m-d');
            $data['Budget']['created'] = null;
            $data['Budget']['modified'] = null;
       
            $this->save($data);
            $id = $this->getLastInsertid();
            
            foreach ($data['BudgetValues'] as $key => $value) {                
                $data['BudgetValues'][$key]['id'] = 0;
                $data['BudgetValues'][$key]['parent_id'] = $id;
                $data['BudgetValues'][$key]['period'] = $next_period->format('Y-m-d');
                $data['BudgetValues'][$key]['cash_fact'] = $this->CashFlow->get_summ_to_article($value['article_id'],$next_period->format('Y-m-01'),$value['type_id']);
                $data['BudgetValues'][$key]['created'] = null;
                $data['BudgetValues'][$key]['modified'] = null;
            }                 
      
            if(isset($data['BudgetValues'][0]['id']))
                $this->BudgetValues->saveAll($data['BudgetValues']);    
            
            $this->recount($id);
            
        }
        
    }
    
}	 