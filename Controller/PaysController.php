<?php

class PaysController extends AppController {
    public $name = 'Pays';
    public $layout = 'maina-layout';
    public $paginate = null;

    public function view()
    {
        $this->set('title', 'Новые');
        $this->set('page', 'org');
        $orgs = $this->Pay->Orgs->find('all',array('conditions' => array('orgs_group_id' => '99')));
        $this->set('orgs',$orgs);

        foreach ($orgs as $org) {
            $count_pay[$org["Orgs"]['id']] = $this->Pay->find('count', array('conditions' => array('Pay.send_utver' => 0,'Pay.arch' => 0, 'Pay.orgs_id' => $org["Orgs"]['id'])));
        }
        $this->set('count', $count_pay);

        $this->ajaxRender('type_view');
    }
    public function arh()
    {
        $this->set('search', 'arh');
        $this->set('title', 'Архив');
        $this->set('page', 'arch');
        $orgs = $this->Pay->Orgs->find('all',array('conditions' => array('orgs_group_id' => '99')));
        $this->set('orgs',$orgs);

        foreach ($orgs as $org) {
            $count_pay[$org["Orgs"]['id']] = $this->Pay->find('count', array('conditions' => array('Pay.send_utver' => 0,'Pay.arch' => 1, 'Pay.orgs_id' => $org["Orgs"]['id'])));
        }
        $this->set('count', $count_pay);

        $this->ajaxRender('type_view');
    }
    public function aun()
    {
        $this->set('search', 'aun');
        $this->set('title', 'На утверждение');
        $this->set('page', 'aunpaids');
        $orgs = $this->Pay->Orgs->find('all',array('conditions' => array('orgs_group_id' => '99')));
        $this->set('orgs',$orgs);

        foreach ($orgs as $org) {
            $count_pay[$org["Orgs"]['id']] = $this->Pay->find('count', array('conditions' => array('Pay.send_utver' => 1,'Pay.arch' => 0, 'Pay.orgs_id' => $org["Orgs"]['id'])));
        }
        $this->set('count', $count_pay);

        $this->ajaxRender('type_view');
    }
    public function org($id = 0)
    {
        $this->set('search', 'org');
        $this->set('arch', '0');
        $this->set_action_menu( array(
        1 => array('name' => 'Отправить на утверждение','icon' => 'fa fa-paper-plane', 'controller' => 'pays','action' => 'sendUtver','class' => 'btn-success')
        ,2 => array('name' => 'Бюджет','icon' => 'fa fa-area-chart','controller' => 'pays','action' => 'budget','class' => 'btn-primary')
        ,3 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'pays','action' => 'edit','class' => 'btn-primary')
        ,4 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'pays','action' => 'delete','class' => 'btn-danger confirm')
        ,5 => array('name' => 'Выгрузить в банк','icon' => 'fa fa-university','controller' => 'pays','action' => 'send_bank','class' => 'btn-primary', 'no_ajax' => 1)
        ,6 => array('name' => 'История','icon' => 'fa fa-history','controller' => 'pays','action' => 'view_history','class' => 'btn-primary')
        ));

        $this->paginate['Pay'] =  array('limit' => 30
        ,'conditions' => array_merge(array('send_utver' => 0,'arch' => 0, 'orgs_id' => $id),$this->search_conditions)
        ,'order' => array('id' => 'DESC')
        );

        $pays = $this->paginate('Pay');
        $this->set('pays', $pays);
        $this->set('id', $id);

        $this->ajaxRender('view');
    }
    public function get_temp_count() {
        return $this->Pay->find('count');
    }

    public function arch($id = 0)
    {
        $this->set('search', 'arch');
        $this->set('arch', '1');
        $this->set_action_menu( array(
        3 => array('name' => 'В новое','icon' => 'fa fa-arrow-left','controller' => 'pays','action' => 'back_news','class' => 'btn-primary')
        ,4 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'pays','action' => 'delete','class' => 'btn-danger confirm')
        ,6 => array('name' => 'История','icon' => 'fa fa-history','controller' => 'pays','action' => 'view_history','class' => 'btn-primary')
        ));
        $this->paginate['Pay'] =  array('limit' => 30
            ,'conditions' => array_merge(array('Pay.arch' => 1, 'Pay.orgs_id' => $id),$this->search_conditions)
            ,'order' => array('id' => 'DESC')
        );

        $pays = $this->paginate('Pay');
        $this->set('pays', $pays);

        $this->ajaxRender('view');
    }
    public function aunpaids($id = 0)
    {
        $this->set('search', 'aunpaids');
        $this->set('arch', '1');
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'pays','action' => 'edit','class' => 'btn-primary')
            ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'pays','action' => 'delete','class' => 'btn-danger confirm')
            ,3 => array('name' => 'Утвердить','icon' => 'fa fa-thumbs-up','controller' => 'pays','action' => 'confirmUtver','class' => 'btn-primary')
            ,4 => array('name' => 'Вернуть','icon' => 'fa fa-paper-plane', 'controller' => 'pays','action' => 'backUtver','class' => 'btn-danger')
          ,6 => array('name' => 'История','icon' => 'fa fa-history','controller' => 'pays','action' => 'view_history','class' => 'btn-primary')
        ));

        $this->paginate['Pay'] =  array('limit' => 30
        ,'conditions' => array_merge(array('Pay.send_utver' => 1,'Pay.arch' => 0, 'Pay.orgs_id' => $id),$this->search_conditions)
        ,'order' => array('id' => 'DESC')
        );

        $pays = $this->paginate('Pay');
        $this->set('pays', $pays);

        $this->ajaxRender('view');
    }
    public function budget($id = 0)
    {
        $this->layout = 'ajax';

        $this->Pay->id = $id;
        $data = $this->Pay->read();
        $this->request->data = $data;

//        $data['Article']['id'] = 23;
        $buget = $this->Pay->query("SELECT * FROM budgets WHERE article_id = " . $data['Article']['id'] . " AND period = '" . date('Y-m') . "-01'");

        if(empty($buget)){
            $this->Session->setFlash('Нет статьи', 'default', array('class' => 'panel-danger'));
            $save = array('Pay' => array(
                'id' => $id,
                'budget' => 0,
                'budg' => 2,
            ));
            $this->Pay->saveAll($save);
            $this->redirect('view/' . $save['Pay']['id']);
        }elseif($buget[0]['budgets']['difference'] >= $data['Pay']['money']){

            $save = array('Pay' => array(
                'id' => $id,
                'budget' => true,
                'budg' => true,
            ));
            if($this->Pay->saveAll($save)) {

                $this->Pay->id = $save['Pay']['id'];

                $this->Session->setFlash('Бюджет прошёл', 'default', array('class' => 'panel-success'));
            }
            else $this->Session->setFlash('Ошибка бюджета!', 'default', array('class' => 'panel-danger'));
            $this->redirect('view/' . $save['Pay']['id']);
        }else{
            $save = array('Pay' => array(
                'id' => $id,
                'budget' => 0,
                'budg' => 0,
            ));
            if($this->Pay->saveAll($save)) {

                $this->Pay->id = $save['Pay']['id'];

                $this->Session->setFlash('Бюджет не прошёл', 'default', array('class' => 'panel-danger'));
            }
            else $this->Session->setFlash('Ошибка бюджета!', 'default', array('class' => 'panel-danger'));
            $this->redirect('view/' . $save['Pay']['id']);
        }

//        echo '<pre>'; print_r($data);echo '</pre>';
        $this->set('text', '');
        $this->render('send_bank');
    }

    public function add($id_aun = 0)
    {
        if($id_aun != 0) {
            $aun = $this->Pay->query("SELECT * FROM aunpaids WHERE id = " . $id_aun . " ");
            $this->Pay->query("UPDATE `aunpaids` SET `paid` = 1 WHERE id = " . $id_aun . " ");

            $aunpaid = $this->Pay->query("SELECT * FROM aunpaids WHERE id = " . $id_aun . " ");

            if (!empty($aunpaid[0]["aunpaids"]["id_bx"])) {
                $postdata = http_build_query([
                    'AUTH_FORM' => 'Y',
                    'TYPE' => 'AUTH',
                    'USER_LOGIN' => 'finbot',
                    'USER_PASSWORD' => 'finbot',
                    'USER_REMEMBER' => 'Y'
                ]);

                $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));

                $context = stream_context_create($opts);
                 file_get_contents('http://portal.stavtrack.ru/fin_close_task.php?ID=' . $aunpaid[0]["aunpaids"]["id_bx"], false, $context);
                

            }
        }
//        echo $this->request->is('ajax');
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }

        $this->request->data = array('Pay' => array('id' => 0
        ,'user_id' => $this->Auth->user('id')
        ));

        /*Вид операции*/
        $report = $this->Pay->PViews->find('list');
        $report[0] = 'Нет';
        ksort($report);
        $this->set('report',$report);

        /*Организация*/
        $organiz = $this->Pay->Orgs->find('list',array('conditions' => array('orgs_group_id' => '99')));
        $organiz[0] = 'Нет';
        ksort($organiz);
        $this->set('organiz',$organiz);

        /*Банк*/
        if($id_aun != 0) {
            $bank = $this->Pay->Account->find('list',  array( 'fields'=> array('bank'),'conditions' => array('org_id' => $aun[0]['aunpaids']['orgs_id'])));
        }
        $bank[0] = 'Нет';
        ksort($bank);

        $this->set('bank',$bank);

        /*Налог*/
        $nalog = $this->Pay->Nalog->find('list');
        $nalog[0] = 'Нет';
        ksort($nalog);
        $this->set('nalog',$nalog);


        $this->set('nds',0);
//        echo "<pre>";print_r($bank);echo "</pre>";

/*Контрагент*/
        $contragents = $this->Pay->Orgs->find('list',array('conditions' =>
            array('orgs_group_id' => $this->Pay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'))))));
        $contragents[0] = 'Нет';
        ksort($contragents);
        $this->set('contragents',$contragents);

/*Банк (контрагента)*/
        if($id_aun != 0) {
            $cbank = $this->Pay->Account->find('list',  array( 'fields'=> array('bank'),'conditions' => array('org_id' => $aun[0]['aunpaids']['contragent_id'])));
        }
        $cbank[0] = 'Нет';
        ksort($cbank);
        $this->set('cbank',$cbank);

/*Вид платежа*/
        $clas = $this->Pay->PPaymentType->find('list');
        $clas[0] = 'Нет';
        ksort($clas);
        $this->set('classif',$clas);

        /*Очередность*/
        $priorit = $this->Pay->PPriorities->find('list');
        $priorit[0] = 'Нет';
        ksort($priorit);
        $this->set('priorit',$priorit);

        /*Статьи*/
        $stati = $this->Pay->Article->find('list',array('conditions' => array('type_id' => '2')));
        $stati[0] = 'Нет';
        ksort($stati);
        $this->set('stati',$stati);

        $auth = $this->Auth->user('id');
        $this->set('auth',$auth);
        $type = "0";
        $this->set('type',$type);

        if($id_aun != 0) {
            $contr = $aun[0]['aunpaids']['contragent_id'];
        }else{
            $contr = '';
        }
        $this->set('contr', $contr);
        $accou = "";
        $this->set('accou', $accou);

        if($id_aun != 0) {
//            $report = $this->Pay->query("SELECT * FROM aunpaids WHERE id = " . $id_aun . " ");
//    echo '<pre>';print_r($this->request->data);echo '</pre>';
            $data["Pay"]["orgs_id"] = $aun[0]['aunpaids']['orgs_id'];
            $data["Pay"]["money"] = $aun[0]['aunpaids']['money'];
            $data["Pay"]["contragent_id"] = $aun[0]['aunpaids']['contragent_id'];
            $data["Pay"]["article_id"] = $aun[0]['aunpaids']['article_id'];
            $this->request->data = $data;
//    echo '<pre>';print_r($this->request->data);echo '</pre>';
        }

        $this->ajaxRender('edit');
        $this->render('edit');
    }

    public function edit($id = 0)
    {
        $this->Pay->id = $id;
        $data = $this->Pay->read();
        $this->request->data = $data;
//        echo '<pre>'; print_r($data);echo '</pre>';
        $due_date = new DateTime($data['Pay']['date']);
        $this->request->data['Pay']['date'] = $due_date->format('d.m.Y H:i:s');

        /*Вид операции*/
        $report = $this->Pay->PViews->find('list');
        $report[0] = 'Нет';
        ksort($report);
        $this->set('report',$report);

        /*Организация*/
        $organiz = $this->Pay->Orgs->find('list',array('conditions' => array('orgs_group_id' => '99')));
        $organiz[0] = 'Нет';
        ksort($organiz);
        $this->set('organiz',$organiz);

        /*Банк*/
        $bank = $this->Pay->Account->find('list',  array( 'fields'=> array('bank'),'conditions' => array('org_id' => $data['Pay']['orgs_id'])));

        $bank[0] = 'Нет';
        ksort($bank);

        $this->set('bank',$bank);

        /*Налог*/
        $nalog = $this->Pay->Nalog->find('list');
        $nalog[0] = 'Нет';
        ksort($nalog);
        $this->set('nalog',$nalog);


        $this->set('nds',$data['Pay']['money_nds']);
//        echo "<pre>";print_r($data);echo "</pre>";

        /*Контрагент*/
        $contragents = $this->Pay->Orgs->find('list',array('conditions' =>
            array('orgs_group_id' => $this->Pay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'))))));
        $contragents[0] = 'Нет';
        ksort($contragents);
        $this->set('contragents',$contragents);

        /*Банк (контрагента)*/
        $cbank = $this->Pay->Account->find('list',  array( 'fields'=> array('bank'),'conditions' => array('org_id' => $data['Contragent']['id'])));

        $cbank[0] = 'Нет';
        ksort($cbank);
        $this->set('cbank',$cbank);

        /*Вид платежа*/
        $clas = $this->Pay->PPaymentType->find('list');
        $clas[0] = 'Нет';
        ksort($clas);
        $this->set('classif',$clas);

        /*Очередность*/
        $priorit = $this->Pay->PPriorities->find('list');
        $priorit[0] = 'Нет';
        ksort($priorit);
        $this->set('priorit',$priorit);

        /*Статьи*/
        $stati = $this->Pay->Article->find('list',array('conditions' => array('type_id' => '2')));
        $stati[0] = 'Нет';
        ksort($stati);
        $this->set('stati',$stati);

        $type = "1";
        $this->set('type',$type);
        $contr = "";
        $this->set('contr', $this->data['Contragent']['id']);
        $accou = "";
        $this->set('accou', $this->data['Account']['id']);

        $auth = $this->Auth->user('id');
        $this->set('auth',$auth);

        $this->ajaxRender();
    }

    public function save()
    {
      if(isset($this->data['Pay'])&&!isset($this->data['cancel']))
        {
            $due_date = new DateTime($this->data['Pay']['date']);
            $this->request->data['Pay']['date'] = $due_date->format('Y-m-d H:i:s');

            if($this->Pay->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));


            $id = $this->request->data['Pay']['id'];
            if($id == 0) $id = $this->Pay->getLastInsertid();

            $user_id = $this->request->data['Pay']['user_id'];
            $type = $this->request->data['Pay']['type'];

            $data = array(
            'type' => $type
            ,'pay_id' => $id
            ,'user_id' => $user_id
            ,'modified' => date('Y-m-d H:i:s')
            );
            $this->Pay->PayHistory->save($data);
        }
//        debug($this->data);
        $this->redirect('view');
    }

    public function back_news($pay_id)
    {
        $data = array('Pay' => array(
            'id' => $pay_id,
            'arch' => false,
        ));
        if($this->Pay->saveAll($data)) {

            $this->Pay->id = $data['Pay']['id'];

            $this->Session->setFlash('Платеж отправлен в новое.', 'default', array('class' => 'panel-success'));
        }
        else $this->Session->setFlash('Ошибка платежа!', 'default', array('class' => 'panel-danger'));

        $this->Pay->id = $pay_id;
        $datas = $this->Pay->read(); 
        $this->redirect('arch/' . $datas['Pay']['orgs_id']);
    }
    public function sendUtver($pay_id)
    {
        $data = array('Pay' => array(
            'id' => $pay_id,
            'send_utver' => true,
            'utver' => false
        ));
        if($this->Pay->saveAll($data)) {

            $this->Pay->id = $data['Pay']['id'];

            $user = $this->Auth->user('id');
            $this->Pay->query("INSERT INTO `pay_histories` (`user_id`, `pay_id`, `modified`, `type`) VALUES ('".$user."','".$pay_id."','".date('Y-m-d H:i:s')."','2')");

            $this->Session->setFlash('Платеж отправлен на утверждение.', 'default', array('class' => 'panel-success'));
        }
        else $this->Session->setFlash('Ошибка платежа!', 'default', array('class' => 'panel-danger'));

        $this->Pay->id = $pay_id;
        $datas = $this->Pay->read(); 
        $this->redirect('org/' .  $datas['Pay']['orgs_id']);
    }
    public function backUtver($pay_id)
    {
        $data = array('Pay' => array(
            'id' => $pay_id,
            'send_utver' => false,
            'utver' => false
        ));
        if($this->Pay->saveAll($data)) {

            $this->Pay->id = $data['Pay']['id'];

            $user = $this->Auth->user('id');
            $this->Pay->query("INSERT INTO `pay_histories` (`user_id`, `pay_id`, `modified`, `type`) VALUES ('".$user."','".$pay_id."','".date('Y-m-d H:i:s')."','3')");

            $this->Session->setFlash('Платеж отправлеа назад.', 'default', array('class' => 'panel-success'));
        }
        else $this->Session->setFlash('Ошибка платежа!', 'default', array('class' => 'panel-danger'));

        $this->Pay->id = $pay_id;
        $datas = $this->Pay->read(); 
        $this->redirect('aunpaids/' .$datas['Pay']['orgs_id']);
    }

    public function confirmUtver($pay_id)
    {
//        echo "<pre>";print_r($this);echo "</pre>";
        $data = array('Pay' => array(
            'id' => $pay_id,
            'send_utver' => false,
            'utver' => true,
            'budg' => true
        ));
        if($this->Pay->saveAll($data)) {

            $this->Pay->id = $data['Pay']['id'];

            $user = $this->Auth->user('id');
            $this->Pay->query("INSERT INTO `pay_histories` (`user_id`, `pay_id`, `modified`, `type`) VALUES ('".$user."','".$pay_id."','".date('Y-m-d H:i:s')."','4')");
            $this->Session->setFlash('Платеж утвержден.', 'default', array('class' => 'panel-success'));
        }
        else $this->Session->setFlash('Ошибка платежа!', 'default', array('class' => 'panel-danger'));
        $this->Pay->id = $pay_id;
        $datas = $this->Pay->read(); 
        $this->redirect('aunpaids/' .$datas['Pay']['orgs_id']);

    }

    public function delete($pay_id = 0)
    {
        $this->Pay->id = $pay_id;
        $card = $this->Pay->read();
        if($card['Pay']['utver'] == 0)
        {
            if($this->Pay->delete($pay_id)) {

                $this->Pay->query("DELETE FROM `pay_histories` WHERE pay_id =".$pay_id);

                $this->Session->setFlash('Платеж удален.', 'default', array('class' => 'panel-success'));
            }
            else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));
        } else $this->Session->setFlash('Ошибка удаления - платеж уже утвержден!', 'default', array('class' => 'panel-danger'));
       
        $this->redirect('org/' . $card['Pay']['orgs_id']);

    }

    public function send_bank($id = 0)
    {
        $this->layout = 'ajax';

        $this->Pay->id = $id;
        $data = $this->Pay->read();
        $this->request->data = $data;
        if($data['Pay']['budget'] != '1'){
            $this->Session->setFlash('Не прошёл бюджет!', 'default', array('class' => 'panel-danger'));
            $this->redirect('org/' . $data['Pay']['orgs_id']);

        }else{


//echo '<pre>';print_r($data);echo '</pre>';
$text = "1CClientBankExchange
ВерсияФормата=1.02
Кодировка=Windows
Отправитель=Финансы 1.0
Получатель=Система Клиент-Банк \"BClient\"
ДатаСоздания=".date('d.m.Y')."
ВремяСоздания=".date('H:i:s')."
ДатаНачала=".date('d.m.Y')."
ДатаКонца=".date('d.m.Y')."
РасчСчет=".$data['orgBank']['account']."
Документ=Платежное поручение
Документ=Платежное требование
";

$text .= "СекцияДокумент=Платежное поручение
";
$text .= "Номер=".$id."
";
$text .= "Дата=".date('d.m.Y')."
";
$text .= "Сумма=" . number_format($data['Pay']['money'], 2, '.', '') . "
";
$text .= "ПлательщикСчет=".$data['orgBank']['account']."
";
$text .= "Плательщик=ИНН ".$data['Orgs']['inn']." ".$data['Orgs']['name']."
";
$text .= "ПлательщикИНН=".$data['Orgs']['inn']."
";
$text .= "Плательщик1=".$data['Orgs']['name']."
";
$text .= "ПлательщикРасчСчет=".$data['orgBank']['account']."
";
$text .= "ПлательщикБанк1=".$data['orgBank']['bank']."
";
$text .= "ПлательщикБанк2=".$data['orgBank']['city_bank']."
";
$text .= "ПлательщикБИК=".$data['orgBank']['bik']."
";
$text .= "ПлательщикКорсчет=".$data['orgBank']['kors']."
";

            $text .= "ПолучательСчет=".$data['Account']['account']."
";
            $text .= "Получатель=ИНН ".$data['Contragent']['inn']." ".$data['Contragent']['name']."
";
            $text .= "ПолучательИНН=".$data['Contragent']['inn']."
";
            $text .= "Получатель1=".$data['Contragent']['name']."
";
            $text .= "ПолучательРасчСчет=".$data['Account']['account']."
";
            $text .= "ПолучательБанк1=".$data['Account']['bank']."
";
            $text .= "ПолучательБанк2=".$data['Account']['city_bank']."
";
            $text .= "ПолучательБИК=".$data['Account']['bik']."
";
            $text .= "ПолучательКорсчет=".$data['Account']['kors']."
";
            $text .= "ВидОплаты=".$data['PPaymentType']['bank_id']."
";
            $text .= "ПолучательКПП=0
";
            $text .= "Очередность=".$data['PPriorities']['bank_id']."
";

$naznac = str_replace(array("\r", "\n"), '',$data['Pay']['point_payment']);
$text .= "НазначениеПлатежа=".$naznac."
";
preg_match_all('#Сумма.+?Сумма НДС.+?руб.(.*)#is', $naznac, $arr);
        if(empty($arr[1][0])){
            preg_match_all('#Сумма.+?руб.(.*)#is', $naznac, $arr);
        }
$text .= "НазначениеПлатежа1=".$arr[1][0]."
";
$text .= "НазначениеПлатежа2=Сумма ".number_format($data['Pay']['money'], 2, '-', '')."
";
if($data['Pay']['money_nds'] > 1){
    $text .= "НазначениеПлатежа3=Сумма В т.ч. НДС (18%)  ".number_format($data['Pay']['money_nds'], 2, '-', '')."
";
}else {
    $text .= "НазначениеПлатежа3=Без налога (НДС)
";
}
if(!empty($data['Pay']['payment']))
$text .= "Код=".$data['Pay']['payment']."
";

$text .= "КонецДокумента
";

$text .= "КонецФайла";

            $dataSave = array('Pay' => array(
                'id' => $data['Pay']['id'],
                'arch' => true,
            ));
            $this->Pay->saveAll($dataSave);

            $user = $this->Auth->user('id');
            $this->Pay->query("INSERT INTO `pay_histories` (`user_id`, `pay_id`, `modified`, `type`) VALUES ('".$user."','".$data['Pay']['id']."','".date('Y-m-d H:i:s')."','5')");

            $text = iconv('UTF-8', 'windows-1251//TRANSLIT', $text);
        $this->set('text',$text);
        $this->response->disableCache();
        $this->response->type('text/plain; charset=windows-1251');
        $this->response->header(array(
            'Pragma: public',
            'Cache-Control: must-revalidate, post-check=0, pre-check=0',
            'Expires: 0',
            'Content-Disposition: attachment; filename='.basename('export_bank.txt'),
        ));
}
//        $this->ajaxRender();
//        $this->redirect('send_bank');
    }

    public function exp($id = 0)
    {
        $this->layout = 'ajax';

        if(empty($this->data['Pay']['modify'])){
         
            $this->Session->setFlash('Ничего не выбрано!', 'default', array('class' => 'panel-danger'));
            $this->redirect('org/' . $this->data['Pay']['page']);
        }else{


        $datas = $this->Pay->find('all',  array('conditions' => array('Pay.id' => $this->data['Pay']['modify'])));
 // echo'<pre>'.print_r($datas).'</pre>';

        $text = "1CClientBankExchange
ВерсияФормата=1.02
Кодировка=Windows
Отправитель=Финансы 1.0
Получатель=Система Клиент-Банк \"BClient\"
ДатаСоздания=".date('d.m.Y')."
ВремяСоздания=".date('H:i:s')."
ДатаНачала=".date('d.m.Y')."
ДатаКонца=".date('d.m.Y')."
РасчСчет=".$datas[0]['orgBank']['account']."
Документ=Платежное поручение
Документ=Платежное требование
";

//        $this->request->data = $data;
//        echo'<pre>'.print_r($datas).'</pre>';
foreach ($datas as $data){
//echo '<pre>';print_r($data);echo '</pre>';


$text .= "СекцияДокумент=Платежное поручение
";
$text .= "Номер=".$data['Pay']['id']."
";
$text .= "Дата=".date('d.m.Y')."
";
$text .= "Сумма=" . number_format($data['Pay']['money'], 2, '.', '') . "
";
$text .= "ПлательщикСчет=".$data['orgBank']['account']."
";
$text .= "Плательщик=ИНН ".$data['Orgs']['inn']." ".$data['Orgs']['name']."
";
$text .= "ПлательщикИНН=".$data['Orgs']['inn']."
";
$text .= "Плательщик1=".$data['Orgs']['name']."
";
$text .= "ПлательщикРасчСчет=".$data['orgBank']['account']."
";
$text .= "ПлательщикБанк1=".$data['orgBank']['bank']."
";
$text .= "ПлательщикБанк2=".$data['orgBank']['city_bank']."
";
$text .= "ПлательщикБИК=".$data['orgBank']['bik']."
";
$text .= "ПлательщикКорсчет=".$data['orgBank']['kors']."
";

            $text .= "ПолучательСчет=".$data['Account']['account']."
";
            $text .= "Получатель=ИНН ".$data['Contragent']['inn']." ".$data['Contragent']['name']."
";
            $text .= "ПолучательИНН=".$data['Contragent']['inn']."
";
            $text .= "Получатель1=".$data['Contragent']['name']."
";
            $text .= "ПолучательРасчСчет=".$data['Account']['account']."
";
            $text .= "ПолучательБанк1=".$data['Account']['bank']."
";
            $text .= "ПолучательБанк2=".$data['Account']['city_bank']."
";
            $text .= "ПолучательБИК=".$data['Account']['bik']."
";
            $text .= "ПолучательКорсчет=".$data['Account']['kors']."
";
            $text .= "ВидОплаты=".$data['PPaymentType']['bank_id']."
";
            $text .= "ПолучательКПП=0
";
            $text .= "Очередность=".$data['PPriorities']['bank_id']."
";

$naznac = str_replace(array("\r", "\n"), '',$data['Pay']['point_payment']);
$text .= "НазначениеПлатежа=".$naznac."
";
preg_match_all('#Сумма.+?Сумма НДС.+?руб.(.*)#is', $naznac, $arr);
if(empty($arr[1][0])){
    preg_match_all('#Сумма.+?руб.(.*)#is', $naznac, $arr);
}
$text .= "НазначениеПлатежа1=".$arr[1][0]."
";
$text .= "НазначениеПлатежа2=Сумма ".number_format($data['Pay']['money'], 2, '-', '')."
";
if($data['Pay']['money_nds'] > 1){
    $text .= "НазначениеПлатежа3=Сумма В т.ч. НДС (18%) ".number_format($data['Pay']['money_nds'], 2, '-', '')."
";
}else {
    $text .= "НазначениеПлатежа3=Без налога (НДС)
";
}
if(!empty($data['Pay']['payment']))
$text .= "Код=".$data['Pay']['payment']."
";
$text .= "КонецДокумента
";

    $dataSave = array('Pay' => array(
        'id' => $data['Pay']['id'],
        'arch' => true,
    ));

    $user = $this->Auth->user('id');
    $this->Pay->query("INSERT INTO `pay_histories` (`user_id`, `pay_id`, `modified`, `type`) VALUES ('".$user."','".$data['Pay']['id']."','".date('Y-m-d H:i:s')."','5')");

    $this->Pay->saveAll($dataSave);
}
$text .= "КонецФайла";
            $text = iconv('UTF-8', 'windows-1251//TRANSLIT', $text);
        $this->set('text',$text);
        $this->response->disableCache();
        $this->response->type('text/plain; charset=windows-1251');
        $this->response->header(array(
            'Pragma: public',
            'Cache-Control: must-revalidate, post-check=0, pre-check=0',
            'Expires: 0',
            'Content-Disposition: attachment; filename='.basename('export_bank.txt'),
        ));
    }
//        $this->ajaxRender();
//        $this->redirect('send_bank');
//        $this->ajaxRender('send_bank');
        $this->render('send_bank');
    }
    public function view_history($id = 0)
    {
        $user = $this->Pay->User->find('list',array('fields' => array('id','username')));
        $this->set('user',$user);

        /**
         * 0 - создал
         * 1 - изменил
         * 2- отправил на утверж
         * 3 - вернул из утв
         * 4 - утвердил
         * 5 - В банк
         */

        $type = ['Создал','Изменил','Отправил на утверждение','Вернул из утверждения','Утвердил','Сформирован в банк'];
        $this->set('type',$type);

        $this->Pay->id = $id;
        $data = $this->Pay->read();
        $this->request->data = $data;

        $this->ajaxRender();
    }

    public function get_search_conditions($search_text = null) {
        $conditions = array(
            'OR' => array(
                'Contragent.name LIKE "%' . $search_text . '%"'
                ,'Orgs.name LIKE "%' . $search_text . '%"'
                ,'Article.name LIKE "%' . $search_text . '%"'
                ,'Pay.point_payment LIKE "%' . $search_text . '%"'
                ,'Account.bank LIKE "%' . $search_text . '%"'
                ,'PViews.name LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }

}
?>