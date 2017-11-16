<?php

class CashFlowController extends AppController {
    public $name = 'CashFlow';
    public $layout = 'maina-layout';
    public $helpers = array();
    public $paginate = null;
    //public $uses = array('Sources');


    public function beforeFilter() {

    }

    public function cash_tmp() {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'cash_flow','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'cash_flow','action' => 'delete','class' => 'btn-danger confirm')
        ));

        $this->paginate['CashFlow'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('CashFlow.type_id' => 0),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));
        $cash_tmp = $this->paginate('CashFlow');
        $this->set('cash_tmp',$cash_tmp);

        $this->ajaxRender('cash_tmp', null, array('action' => 'update-temp-count', 'target' => '#main', 'param' => $this->get_temp_count()));
    }

    public function cash_undefined($type = 'in') {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'cash_flow','action' => 'edit','class' => 'btn-primary')
            ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'cash_flow','action' => 'delete','class' => 'btn-danger confirm')
        ));

        $this->paginate['CashFlow'] =  array('limit' => 30
            ,'conditions' => array_merge(array('CashFlow.article_id' => 0, 'CashFlow.type_id <>' => 0, 'Type.alias' => $type),$this->search_conditions)
            ,'order' => array('created' => 'DESC'));
        $cash_tmp = $this->paginate('CashFlow');
        $this->set('cash_tmp',$cash_tmp);

        $this->ajaxRender('cash_tmp', null, array('action' => 'update-temp-count', 'target' => '#main', 'param' => $this->get_temp_count()));
    }

    /*
     * Доходы
     *
     * @return void
     */
    public function cash_in() {
        $this->set_action_menu( array(
            //1 => array('name' => 'Распределить','icon' => 'fa fa-exchange','controller' => 'cash_flow','action' => 'reallocate','class' => 'btn-primary'),
           2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'cash_flow','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Копировать','icon' => 'fa fa-copy','controller' => 'cash_flow','action' => 'copy_operation','class' => 'btn-success  confirm')
           ,4 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'cash_flow','action' => 'delete','class' => 'btn-danger confirm')
        ));

        $this->paginate['CashFlow'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('CashFlow.type_id' => $this->CashFlow->Type->get_id_in(), 'CashFlow.article_id <>' => 0),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));
//var_dump($this->paginate['CashFlow']);die();
        $cash_in = $this->paginate('CashFlow');

        $this->set('cash_in',$cash_in);

        $this->request->data['FilterBox'] = $this->set_filter_option($this->CashFlow->Type->get_id_in());
        if(!empty($this->search_conditions))
            $this->request->data['FilterBox']['filtered'] = true;
        else $this->request->data['FilterBox']['filtered'] = false;
        $this->ajaxRender('cash_in');
    }

    public function add_cash_flow_in() {
        $type = $this->CashFlow->Type->get_id_in();
        $this->request->data = array('CashFlow' => array('id' => 0, 'type_id' => $type, 'pay_type_id' => 1,
            'Contragents' => $this->CashFlow->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->CashFlow->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id != 99')))))),
            'Articles' => $this->CashFlow->Article->get_list(array('conditions' => array('type_id' => $type))),
            'Nomenclatures' => array(),
            'PayTypes' => $this->CashFlow->PayType->find('list'),
            'Orgs' => $this->CashFlow->Org->get_list(array('conditions' => array('orgs_group_id' => 99))),
            'Classificators' => $this->CashFlow->Classificator->find('list',array('conditions' => array('type_id' => $type))),
            'AccountOrg' => array(0 => 'Не задан'),
            'AccountContragent' => array(0 => 'Не задан'),
        ));
        $this->set('view','cash_in');
        $this->ajaxRender('edit');
        $this->render('edit');
    }

    public function cash_out() {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'cash_flow','action' => 'edit','class' => 'btn-primary')
           ,2 => array('name' => 'Копировать','icon' => 'fa fa-copy','controller' => 'cash_flow','action' => 'copy_operation','class' => 'btn-success  confirm')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'cash_flow','action' => 'delete','class' => 'btn-danger confirm')
        ));

        $this->paginate['CashFlow'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('CashFlow.type_id' => $this->CashFlow->Type->get_id_out(), 'CashFlow.article_id <>' => 0),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));

        $cash_out = $this->paginate('CashFlow');
        $this->set('cash_out',$cash_out);

        $this->request->data['FilterBox'] = $this->set_filter_option($this->CashFlow->Type->get_id_out());
        if(!empty($this->search_conditions))
            $this->request->data['FilterBox']['filtered'] = true;
        else $this->request->data['FilterBox']['filtered'] = false;
        $this->ajaxRender('cash_out');
    }

    public function add_cash_flow_out() {
        $type = $this->CashFlow->Type->get_id_out();
        $this->request->data = array('CashFlow' => array('id' => 0, 'type_id' => $type, 'pay_type_id' => 1,
            'Contragents' => $this->CashFlow->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->CashFlow->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id != 99')))))),
            'Articles' => $this->CashFlow->Article->get_list(array('conditions' => array('type_id' => $type))),
            'Nomenclatures' => array(),
            'PayTypes' => $this->CashFlow->PayType->find('list'),
            'Orgs' => $this->CashFlow->Org->get_list(array('conditions' => array('orgs_group_id' => 99))),
            'Classificators' => $this->CashFlow->Classificator->find('list',array('conditions' => array('type_id' => $type))),
            'AccountOrg' => array(0 => 'Не задан'),
            'AccountContragent' => array(0 => 'Не задан'),
            'Sources' => $this->CashFlow->Source->find('list')
        ));
        $this->set('view','cash_out');
        $this->ajaxRender('edit');
        $this->render('edit');
    }

    public function edit($id = 0, $is_copy = false) {
        $this->CashFlow->id = $id;
        $data = $this->CashFlow->read();
        $type = $data['CashFlow']['type_id'];
        $data['CashFlow']['Types'] = $this->CashFlow->Type->get_list();
        $data['CashFlow']['Contragents'] = $this->CashFlow->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->CashFlow->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id != 99'))))));
        $data['CashFlow']['Articles'] = $this->CashFlow->Article->get_list(array('conditions' => array('type_id' => $type)));
        $data['CashFlow']['Nomenclatures'] = $this->CashFlow->Nomenclature->get_list(array('conditions' => array('article_id' => $data['CashFlow']['article_id'])));
        $data['CashFlow']['PayTypes'] = $this->CashFlow->PayType->find('list');
        $data['CashFlow']['Orgs'] = $this->CashFlow->Org->find('list',array('conditions' => array('orgs_group_id' => 99)));
        $data['CashFlow']['Classificators'] = $this->CashFlow->Classificator->find('list',array('conditions' => array('type_id' => $type)));

        $data['CashFlow']['AccountOrg'] = $this->CashFlow->AccountOrg->get_list(array(
                'fields' => array('id','bank'),'conditions' => array('org_id' => $data['CashFlow']['org_id'])));
        $data['CashFlow']['AccountContragent'] = $this->CashFlow->AccountContragent->get_list(array(
                'fields' => array('id','bank'),'conditions' => array('org_id' => $data['CashFlow']['contragent_id'])));
        $data['CashFlow']['Sources'] = $this->CashFlow->Source->find('list');
        $date = new DateTime($data['CashFlow']['created']);
        $data['CashFlow']['created'] = $date->format('d.m.Y');

        if($this->CashFlow->Type->is_in($type))
            $view = 'cash_in';
        else if($this->CashFlow->Type->is_out($type))
            $view = 'cash_out';
        else {
            $view = 'cash_tmp';
            //Если редактор открыт для невыясненных
            $data['CashFlow']['is_tmp_type'] = 1;
        }

        if($is_copy)
            $data['CashFlow']['id'] = 0; //если собираемся копировать запись

        $this->request->data = $data;

        $this->set('view',$view);
        $this->ajaxRender('edit');
    }

    public function change() {
        //$this->request->data = $this->data;

        $type = $this->data['CashFlow']['type_id'];
        $this->request->data['CashFlow']['Types'] = $this->CashFlow->Type->get_list();
        $this->request->data['CashFlow']['Contragents'] = $this->CashFlow->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->CashFlow->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id != 99'))))));
        $this->request->data['CashFlow']['Articles'] = $this->CashFlow->Article->get_list(array('conditions' => array('type_id' => $type)));
        $this->request->data['CashFlow']['Nomenclatures'] = $this->CashFlow->Nomenclature->get_list(array('conditions' => array('Nomenclature.article_id' => $this->data['CashFlow']['article_id'])));
        $this->request->data['CashFlow']['PayTypes'] = $this->CashFlow->PayType->find('list');
        $this->request->data['CashFlow']['Orgs'] = $this->CashFlow->Org->find('list',array('conditions' => array('orgs_group_id' => 99)));
        $this->request->data['CashFlow']['Classificators'] = $this->CashFlow->Classificator->find('list',array('conditions' => array('type_id' => $type)));

        $this->request->data['CashFlow']['AccountOrg'] = $this->CashFlow->AccountOrg->get_list(array(
                'fields' => array('id','bank'),'conditions' => array('org_id' => $this->data['CashFlow']['org_id'])));
        $this->request->data['CashFlow']['AccountContragent'] = $this->CashFlow->AccountContragent->get_list(array(
                'fields' => array('id','bank'),'conditions' => array('org_id' => $this->data['CashFlow']['contragent_id'])));
        $this->request->data['CashFlow']['Sources'] = $this->CashFlow->Source->find('list');
        if($this->CashFlow->Type->is_in($type))
            $view = 'cash_in';
        else $view = 'cash_out';
        $this->set('view',$view);

        $this->ajaxRender('edit');
        $this->render('edit');
    }

    public function save() {
        if(isset($this->data['CashFlow'])&&!isset($this->data['cancel']))
        {
            if(!isset($this->data['CashFlow']['org_account_id'])) $this->request->data['CashFlow']['org_account_id'] = 0;
            if(!isset($this->data['CashFlow']['contragent_account_id'])) $this->request->data['CashFlow']['contragent_account_id'] = 0;
            $this->request->data['CashFlow']['user_id'] = $this->Auth->user('id');
            $created = new DateTime($this->data['CashFlow']['created']);
            $this->request->data['CashFlow']['created'] = $created->format('Y-m-d 00:00:00');

            if($this->validate_data('CashFlow',$this->data)&&$this->CashFlow->save_data($this->data))
            {
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            }
        }

        if($this->CashFlow->Type->is_in($this->data['CashFlow']['type_id']))
            $this->redirect('cash_in');
        else if($this->CashFlow->Type->is_out($this->data['CashFlow']['type_id']))
            $this->redirect('cash_out');
        else $this->redirect('cash_tmp');

    }

    public function delete($id = 0) {
        $this->CashFlow->id = $id;
        $cash = $this->CashFlow->read();

        if($this->CashFlow->delete($id)) {
            $period = new DateTime($cash['CashFlow']['created']);

            $this->loadModel('Budget');
            $budget_val = $this->Budget->BudgetValues->find('first',array('conditions' => array(
                'BudgetValues.type_id' => $cash['CashFlow']['type_id'],
                'BudgetValues.article_id' => $cash['CashFlow']['article_id'],
                'BudgetValues.period' => $period->format('Y-m-01')
            )));
            if(!empty($budget_val)) {
                $budget_val['BudgetValues']['cash_fact'] = $this->CashFlow->get_summ_to_article($cash['CashFlow']['article_id'],$period->format('Y-m-01'),$cash['CashFlow']['type_id']);
                $this->Budget->BudgetValues->save($budget_val['BudgetValues']);
                $this->Budget->recount($budget_val['BudgetValues']['parent_id']);
            }
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));
        }
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));

        if($this->CashFlow->Type->is_in($cash['CashFlow']['type_id']))
            $this->redirect('cash_in');
        else if($this->CashFlow->Type->is_out($cash['CashFlow']['type_id']))
            $this->redirect('cash_out');
        else $this->redirect('cash_tmp');
    }

    public function import() {
        $this->ajaxRender();
    }

    public function import_document() {
        $docs = null;
        $file_name = null;
        $import_docs = 0;
        if (!isset($this->data['cancel'])
                    &&isset($this->data['CashFlow']['submittedfile'])
                    && $this->data['CashFlow']['submittedfile']['error'] == 0
                    && is_uploaded_file($this->data['CashFlow']['submittedfile']['tmp_name']))
        {
            $file_name = $this->data['CashFlow']['submittedfile']['name'];
            $file_path = './files/' . $this->data['CashFlow']['submittedfile']['name'];
            move_uploaded_file($this->data['CashFlow']['submittedfile']['tmp_name'], $file_path);

            App::import('Vendor', '1cBank/Bank');
            if (!class_exists('Bank'))
            {
                throw new CakeException('Vendor class Bank not found!');
            }
            $bank = new Bank($file_path); // Запускаем парсинг
            $docs = $bank->getDocs(); //Получаем все спарсенные документы

            $data = $hash_docs = array();
            foreach ($docs as $doc) {
                $hash = md5(serialize($doc));
                if(!in_array($hash,$hash_docs)) {
                    $hash_docs[] = $hash;
                    $contragent_account_id = $org_account_id = $org_id = $contragent_id = $type_id = 0;
                    $payer_acc = $this->CashFlow->Org->Account->find('first',array('conditions' => array('Account.account' => $doc->payeraccount)));
                    $reciever_acc = $this->CashFlow->Org->Account->find('first',array('conditions' => array('Account.account' => $doc->recieveraccount,'Org.inn' => $doc->recieverinn)));
                    $created = new DateTime($doc->docdate);$created = $created->format('Y-m-d');

                    if(!empty($payer_acc)) {
                        if($payer_acc['Org']['orgs_group_id'] == 99) { //расход
                            $org_id = $payer_acc['Org']['id'];
                            $org_account_id = $payer_acc['Account']['id'];
                        } else {//доход
                            $contragent_id = $payer_acc['Org']['id'];
                            $contragent_account_id = $payer_acc['Account']['id'];
                        }
                    }
                    if(!empty($reciever_acc)) {
                        if($reciever_acc['Org']['orgs_group_id'] != 99) {
                            $contragent_id = $reciever_acc['Org']['id'];
                            $contragent_account_id = $reciever_acc['Account']['id'];
                        } else {
                            $org_id = $reciever_acc['Org']['id'];
                            $org_account_id = $reciever_acc['Account']['id'];
                        }
                    }

                    if($contragent_id!=0&&$org_id!=0) {
                        if($payer_acc['Org']['orgs_group_id'] == 99)
                            $type_id = $this->CashFlow->Type->get_id_out(); //расход
                        else $type_id = $this->CashFlow->Type->get_id_in(); //доход
                        $import_docs++;
                    }

                    $data = array('CashFlow' => array(
                        'id' => '0'
                       ,'type_id' => $type_id
                       ,'org_id' => $org_id
                       ,'contragent_id' => $contragent_id
                       ,'org_account_id' => $org_account_id
                       ,'contragent_account_id' => $contragent_account_id
                       ,'cash'=> $doc->summ
                       ,'pay_type_id' => '0'
                       ,'article_id' => '0'
                       ,'nomenclature_id' => '0'
                       ,'quantity' => '0'
                       ,'number' => $doc->inbankid
                       ,'classificator_id' => '0'
                       ,'about' => $doc->paydirection
                       ,'user_id' => $this->Auth->user('id')
                       ,'created' => $created
                           )
                    );

                    if(!empty($data))
                        $this->CashFlow->save_data($data);
                }
            }


            @unlink($file_path);
        }
        $this->set('file_name',$file_name);
        $this->set('count_docs',count($docs));
        $this->set('import_docs',$import_docs);
        $this->ajaxRender();
    }

    public function export($type)
    {
        if($type == 'in') {
            $name = 'Доходы';
            $fields = $this->CashFlow->get_field_list();
            $fields['Classificator.name'] = 'Розница/Опт.';
            $this->CashFlow->set_field_list($fields);
            $this->CashFlow->export($name,array('CashFlow.type_id' => $this->CashFlow->Type->get_id_in()));
        } else {
            $name = 'Расходы';
            $fields = $this->CashFlow->get_field_list();
            $fields['Classificator.name'] = 'Классификация';
            $this->CashFlow->set_field_list($fields);
            $this->CashFlow->export($name,array('CashFlow.type_id' => $this->CashFlow->Type->get_id_out()));
        }
    }

    public function get_search_conditions($search_text = null) {
        $orgs = $this->CashFlow->Org->find('list',array('fields' => array('id') ,'conditions' => array('Org.name LIKE "%' . $search_text . '%"')));
        $users = $this->CashFlow->User->find('list',array('fields' => array('id') ,'conditions' => array('User.username LIKE "%' . $search_text . '%"')));
        $classificators = $this->CashFlow->Classificator->find('list',array('fields' => array('id') ,'conditions' => array('Classificator.name LIKE "%' . $search_text . '%"')));
        $contragents = $this->CashFlow->Contragent->find('list',array('fields' => array('id') ,'conditions' => array('Contragent.name LIKE "%' . $search_text . '%"')));
        $pay_types = $this->CashFlow->PayType->find('list',array('fields' => array('id') ,'conditions' => array('PayType.name LIKE "%' . $search_text . '%"')));
        $articles = $this->CashFlow->Article->find('list',array('fields' => array('id') ,'conditions' => array('Article.name LIKE "%' . $search_text . '%"')));
        $nomenclatures = $this->CashFlow->Nomenclature->find('list',array('fields' => array('id') ,'conditions' => array('Nomenclature.name LIKE "%' . $search_text . '%"')));
        $conditions = array(
            'OR' => array(
               'DATE_FORMAT(CashFlow.created, "%d.%m.%Y") LIKE "%' . $search_text . '%"'
               ,'CashFlow.org_id' => $orgs
               ,'CashFlow.cash LIKE "%' . $search_text . '%"'
               ,'CashFlow.contragent_id' => $contragents
               ,'CashFlow.pay_type_id' => $pay_types
               ,'CashFlow.article_id' => $articles
               ,'CashFlow.nomenclature_id' => $nomenclatures
               ,'CashFlow.number LIKE "%' . $search_text . '%"'
               ,'CashFlow.quantity LIKE "%' . $search_text . '%"'
               ,'CashFlow.about LIKE "%' . $search_text . '%"'
               ,'CashFlow.classificator_id' => $classificators
               ,'CashFlow.about LIKE "%' . $search_text . '%"'
               ,'CashFlow.user_id' => $users
            )
        );
        return $conditions;
    }

    public function get_search_conditions_for_filter_box($filter_box_data = array()) {
 //       $sum = explode(',',$filter_box_data['cash']);
//var_dump($filter_box_data['created']);die();
        $conditions = array(
                //'OR' => array(
                    //array(
                        'CashFlow.cash >' => $filter_box_data['sum_min'],
                        'CashFlow.cash <' => $filter_box_data['sum_max'],
                        "CashFlow.created BETWEEN STR_TO_DATE('" . $filter_box_data['created_b'] . "', '%d.%m.%Y') AND STR_TO_DATE('" . $filter_box_data['created_e'] . "', '%d.%m.%Y') ",

                  //  )
                //)
            );
            if($filter_box_data['org_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.org_id' => $filter_box_data['org_id'], '0 = ' . $filter_box_data['org_id']));
            if($filter_box_data['contragent_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.contragent_id' => $filter_box_data['contragent_id'], '0 = ' . $filter_box_data['contragent_id']));
            if($filter_box_data['pay_type_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.pay_type_id' => $filter_box_data['pay_type_id'], '0 = ' . $filter_box_data['pay_type_id']));
            if($filter_box_data['article_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.article_id' => $filter_box_data['article_id'], '0 = ' . $filter_box_data['article_id']));
            if($filter_box_data['nomenclature_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.nomenclature_id' => $filter_box_data['nomenclature_id'], '0 = ' . $filter_box_data['nomenclature_id']));
            if($filter_box_data['classificator_id'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.classificator_id' => $filter_box_data['classificator_id'], '0 = ' . $filter_box_data['classificator_id']));
            if($filter_box_data['autor'] != 0)
                $conditions['AND'][] = array('OR' => array('CashFlow.autor' => $filter_box_data['autor'], '0 = ' . $filter_box_data['autor']));
            if($filter_box_data['accepted'] != 0)
                $conditions['AND']['CashFlow.accepted'] = $filter_box_data['accepted'];
        return $conditions;
    }

    /*
     * Возвращаем количество невыясненных платежей
     *
     * @return integer
     */
    public function get_temp_count() {
        return $this->CashFlow->find('count',array('conditions' => array('CashFlow.type_id' => 0)));
    }

    /*
     * Создаем и возвращаем копию операции
     *
     * @return void
     */
    public function copy_operation($id = 0) {
         $this->edit($id, true);
    }

    /*
     * Возвращаем данные для фильтрбокса
     *
     * @return array
     */
    public function set_filter_option($type) {
        return array(
            'Orgs' => $this->CashFlow->Org->get_list(array('conditions' => array('orgs_group_id' => 99))),
            'Contragents' => $this->CashFlow->Contragent->get_list(array('conditions' => array('orgs_group_id' => $this->CashFlow->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id != 99')))))),
            'PayTypes' => $this->CashFlow->PayType->get_list(),
            'Articles' => $this->CashFlow->Article->get_list(array('conditions' => array('type_id' => $type))),
            'Nomenclatures' => $this->CashFlow->Nomenclature->get_list(/*array('conditions' => array('Nomenclature.article_id' => $this->data['CashFlow']['article_id']))*/),
            'Classificators' => $this->CashFlow->Classificator->get_list(array('conditions' => array('type_id' => $type))),
            'Autors' => $this->CashFlow->User->get_list(),
            'sum_min' => (isset($this->filter_box_data['sum_min']))?$this->filter_box_data['sum_min']:0,
            'sum_max' => (isset($this->filter_box_data['sum_max']))?$this->filter_box_data['sum_max']:100000000,
            'created_b' => (isset($this->filter_box_data['created_b']))?$this->filter_box_data['created_b']:date('%d.%m.%Y'),
            'created_e' => (isset($this->filter_box_data['created_e']))?$this->filter_box_data['created_e']:date('%d.%m.%Y'),
            'org_id' => (isset($this->filter_box_data['org_id']))?$this->filter_box_data['org_id']:0,
            'contragent_id' => (isset($this->filter_box_data['contragent_id']))?$this->filter_box_data['contragent_id']:0,
            'pay_type_id' => (isset($this->filter_box_data['pay_type_id']))?$this->filter_box_data['pay_type_id']:0,
            'article_id' => (isset($this->filter_box_data['article_id']))?$this->filter_box_data['article_id']:0,
            'nomenclature_id' => (isset($this->filter_box_data['nomenclature_id']))?$this->filter_box_data['nomenclature_id']:0,
            'classificator_id' => (isset($this->filter_box_data['classificator_id']))?$this->filter_box_data['classificator_id']:0,
            'autor' => (isset($this->filter_box_data['autor']))?$this->filter_box_data['autor']:0,
            'accepted' => (isset($this->filter_box_data['accepted']))?$this->filter_box_data['accepted']:0,
        );
    }

}

?>
