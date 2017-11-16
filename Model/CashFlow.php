<?php
class CashFlow extends AppModel
{
    public $belongsTo = array('User','Contragent','Nomenclature','Article','PayType','Org','Type','Classificator','Source',
        'AccountOrg' => array('className' => 'Account','foreignKey' => 'org_account_id'),
        'AccountContragent' => array('className' => 'Account','foreignKey' => 'contragent_account_id')
    );

    public $field_list = array(
       //'id' => 'Уникальный идентификатор'
        'created' => 'Дата',
        'Org.name' => 'Организация',
        'cash' => 'Доход (сумма)',
        'Contragent.name' => 'Контрагент',
        'PayType.name' => 'Способ оплаты',
        'Article.name' => 'Статья',
        'Nomenclature.name' => 'Номенклатура',
        'number' => 'Номер документа',
        'quantity' => 'Количество',
        'Classificator.name' => '',
        'about' => 'Примечание',
        'User.username' => 'Автор',
    );

    var $validate = array(
        /*'nomenclature_id' => array(
            'not0' => array(
                'rule' => array('not0')
                ,'message' => 'Выберите номенклатуру'
            )
        ),*/
        'org_id' => array(
            'not0' => array(
                'rule' => array('not0')
                ,'message' => 'Выберите организация'
            )
        ),
        'contragent_id' => array(
            'not0' => array(
                'rule' => array('not0')
                ,'message' => 'Выберите контрагента'
            )
        ),
        'article_id' => array(
            'not0' => array(
                'rule' => array('not0')
                ,'message' => 'Выберите статью'
            )
        ),
        'cash' => array(
            'notBlank' => array(
                'rule' => array('notBlank')
                ,'message' => 'поле "Сумма" должно быть заполнено'
            ),
        ),
        /*'about' => array(
            'notBlank' => array(
                'rule' => array('notBlank')
                ,'message' => 'поле "Примечание" должно быть заполнено'
            )
        ),*/

    );

    public function get_summ_to_article($article_id = 0, $period = null, $type_id = null) {
        if(is_array($period)) {
            $beg_date = new DateTime($period['beg']);
            $beg_date = $beg_date->format('Y-m-d 00:00:00');
            $end_date = new DateTime($period['end']);
            $end_date = $end_date->format('Y-m-d 23:59:59');
        } else {
            if(!isset($period))
                $period = new DateTime();
            else $period = new DateTime($period);
            $period = new DateTime($period->format('Y-m-01 00:00:00'));
            $beg_date = $period->format('Y-m-01 00:00:00');
            $period->modify('+1 month');
            $end_date = $period->format('Y-m-01 00:00:00');
        }

        if(!isset($type_id))
            $type_id = $this->Type->get_id_out();//по умолчанию расход
        $cash_flows = $this->find('all',array('conditions' => array('CashFlow.article_id' => $article_id
                                                                   ,'CashFlow.type_id' => $type_id
                                                                   ,'CashFlow.created >=' => $beg_date
                                                                   ,'CashFlow.created <=' => $end_date
                                                                    )
        ));

        $summ = 0;
        foreach ($cash_flows as $value) {
            $summ += $value['CashFlow']['cash'];
        }
        return $summ;
    }

    public function get_maxdate_to_article($article_id = 0, $beg_date = null, $end_date = null, $type_id = null) {
        $beg_date = new DateTime($beg_date);
        $beg_date = $beg_date->format('Y-m-d 00:00:00');
        $end_date = new DateTime($end_date);
        $end_date = $end_date->format('Y-m-d 23:59:59');

        if(!isset($type))
            $type_id = $this->Type->get_id_out();//по умолчанию расход
        $cash_flow = $this->find('first',array('conditions' => array('CashFlow.article_id' => $article_id
                                                                   ,'CashFlow.type_id' => $type_id
                                                                   ,'CashFlow.created >=' => $beg_date
                                                                   ,'CashFlow.created <=' => $end_date
                                                                    )
                                             ,'order' => array('CashFlow.created DESC')
        ));
        if(empty($cash_flow)) return $beg_date;
        else return $cash_flow['CashFlow']['created'];
     }

     public function get_mindate_to_article($article_id = 0, $beg_date = null, $end_date = null, $type_id = null) {
        $beg_date = new DateTime($beg_date);
        $beg_date = $beg_date->format('Y-m-d 00:00:00');
        $end_date = new DateTime($end_date);
        $end_date = $end_date->format('Y-m-d 23:59:59');

        if(!isset($type))
            $type_id = $this->Type->get_id_out();//по умолчанию расход
        $cash_flow = $this->find('first',array('conditions' => array('CashFlow.article_id' => $article_id
                                                                   ,'CashFlow.type_id' => $type_id
                                                                   ,'CashFlow.created >=' => $beg_date
                                                                   ,'CashFlow.created <=' => $end_date
                                                                    )
                                             ,'order' => array('CashFlow.created ASC')
        ));
        if(empty($cash_flow)) return $beg_date;
        else return $cash_flow['CashFlow']['created'];
    }

    public function save_data($data = null) {
        if(isset($data))
        {
            if($this->saveAll($data,array('validate' => false)))
            {
                if(isset($data['CashFlow']['created']))
                    $period = new DateTime($data['CashFlow']['created']);
                else
                    $period = new DateTime();
                App::import('model','Budget');
                $this->Budget = new Budget();
                $budget_val = $this->Budget->BudgetValues->find('first',array('conditions' => array(
                    'BudgetValues.type_id' => $data['CashFlow']['type_id'],
                    'BudgetValues.article_id' => $data['CashFlow']['article_id'],
                    'BudgetValues.period' => $period->format('Y-m-01')
                )));
                if(!empty($budget_val)) {
                    $budget_val['BudgetValues']['cash_fact'] = $this->get_summ_to_article($data['CashFlow']['article_id'],$period->format('Y-m-01'),$data['CashFlow']['type_id']);
                    $this->Budget->BudgetValues->save($budget_val['BudgetValues']);
                    $this->Budget->recount($budget_val['BudgetValues']['parent_id']);
                }
                return true;
            }
        }
        return false;
    }

}