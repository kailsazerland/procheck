<?php

class AunpaidsController extends AppController
{
    public $name = 'Aunpaids';
    public $layout = 'maina-layout';
    public $paginate = null;

    public function view()
    {
        $this->set('search', 'view');
        $this->set('paid', '0');
        $this->set_action_menu(array(
            1 => array('name' => 'Отправить на утверждение', 'title' => 'Отправить на утверждение', 'icon' => 'fa fa-paper-plane', 'controller' => 'aunpaids', 'action' => 'sendUtver', 'class' => 'btn-success')
        , 2 => array('name' => 'Утвердить', 'icon' => 'fa fa-thumbs-up', 'controller' => 'aunpaids', 'action' => 'confirmUtver', 'class' => 'btn-primary')
        , 3 => array('name' => 'Редактировать', 'icon' => 'fa fa-pencil', 'controller' => 'aunpaids', 'action' => 'edit', 'class' => 'btn-primary')
        , 4 => array('name' => 'Удалить', 'icon' => 'fa fa-trash', 'controller' => 'aunpaids', 'action' => 'delete', 'class' => 'btn-danger confirm')
        , 5 => array('name' => 'Платеж', 'icon' => 'fa fa-credit-card', 'controller' => 'pays', 'action' => 'add', 'class' => 'btn-primary')
        , 6 => array('name' => 'Оплачено', 'icon' => 'fa fa-money', 'controller' => 'aunpaids', 'action' => 'on_money', 'class' => 'btn-primary')
        , 7 => array('name' => 'История', 'icon' => 'fa fa-history', 'controller' => 'aunpaids', 'action' => 'view_history', 'class' => 'btn-primary')
        ));
//        $au = $this->Aunpaid->find('list');
//        echo "<pre>";print_r($this->search_conditions);echo "</pre>";

        $this->paginate['Aunpaid'] = array('limit' => 30
        , 'conditions' => array_merge(array('Aunpaid.paid' => 0, 'Aunpaid.send_utver' => 0), $this->search_conditions)
        , 'order' => array('id' => 'DESC')
        );

        $aunps = $this->paginate('Aunpaid');
        $this->set('aunps', $aunps);
        $this->ajaxRender('view', null, array('action' => 'update-temp-count', 'target' => '#main', 'param' => $this->get_temp_count()));
    }

    public function get_temp_count()
    {
        return $this->Aunpaid->find('count');
    }

    public function paid()
    {
        $this->set('search', 'paid');
        $this->set('paid', '1');
        $this->set_action_menu(array(

            3 => array('name' => 'Редактировать', 'icon' => 'fa fa-pencil', 'controller' => 'aunpaids', 'action' => 'edit', 'class' => 'btn-primary')
        , 4 => array('name' => 'Удалить', 'icon' => 'fa fa-trash', 'controller' => 'aunpaids', 'action' => 'delete', 'class' => 'btn-danger confirm')
        , 6 => array('name' => 'Не оплачено', 'icon' => 'fa fa-money', 'controller' => 'aunpaids', 'action' => 'off_money', 'class' => 'btn-primary')

        , 7 => array('name' => 'История', 'icon' => 'fa fa-history', 'controller' => 'aunpaids', 'action' => 'view_history', 'class' => 'btn-primary')
        ));

        $this->paginate['Aunpaid'] = array('limit' => 30
        , 'conditions' => array_merge(array('Aunpaid.paid' => 1), $this->search_conditions)
        , 'order' => array('id' => 'DESC')
        );

        $aunps = $this->paginate('Aunpaid');
        $this->set('aunps', $aunps);

        $this->ajaxRender('view');
    }

    public function approval()
    {
        $this->set('search', 'approval');
        $this->set('paid', '1');
        $this->set_action_menu(array(


            1 => array('name' => 'Редактировать', 'icon' => 'fa fa-pencil', 'controller' => 'aunpaids', 'action' => 'edit', 'class' => 'btn-primary')
        , 2 => array('name' => 'Удалить', 'icon' => 'fa fa-trash', 'controller' => 'aunpaids', 'action' => 'delete', 'class' => 'btn-danger confirm')
        , 3 => array('name' => 'Утвердить', 'icon' => 'fa fa-thumbs-up', 'controller' => 'aunpaids', 'action' => 'confirmUtver', 'class' => 'btn-primary')
        , 4 => array('name' => 'Вернуть', 'icon' => 'fa fa-paper-plane', 'controller' => 'aunpaids', 'action' => 'backUtver', 'class' => 'btn-danger')

        , 7 => array('name' => 'История', 'icon' => 'fa fa-history', 'controller' => 'aunpaids', 'action' => 'view_history', 'class' => 'btn-primary')
        ));
//        $au = $this->Aunpaid->find('list');
//        echo "<pre>";print_r($this->search_conditions);echo "</pre>";
        $this->paginate['Aunpaid'] = array('limit' => 30
        , 'conditions' => array_merge(array('Aunpaid.send_utver' => 1,), $this->search_conditions)
        , 'order' => array('id' => 'DESC')
        );

        $aunps = $this->paginate('Aunpaid');
        $this->set('aunps', $aunps);

        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Aunpaid' => array('id' => 0
        , 'user_id' => $this->Auth->user('id')
        ));

        $organiz = $this->Aunpaid->Orgs->find('list', array('conditions' => array('orgs_group_id' => '99')));
        $organiz[0] = 'Нет';
        ksort($organiz);
        $this->set('organiz', $organiz);

//        echo "<pre>";print_r($organiz);echo "</pre>";

        $contragents = $this->Aunpaid->Orgs->find('list', array('conditions' =>
            array('orgs_group_id' => $this->Aunpaid->Contragent->OrgsGroup->find('list', array('fields' => array('id', 'id'))))));
        $contragents[0] = 'Нет';
        ksort($contragents);
        $this->set('contragents', $contragents);

        $pay_types = $this->Aunpaid->PayType->find('list');
        $pay_types[0] = 'Нет';
        ksort($pay_types);
        $this->set('pay_types', $pay_types);

        $stati = $this->Aunpaid->Article->find('list', array('conditions' => array('type_id' => '2')));
        $stati[0] = 'Нет';
        ksort($stati);
        $this->set('stati', $stati);

        $classif = $this->Aunpaid->query("SELECT id, name FROM classificators WHERE type_id = 2");

        $clas = array();
        foreach ($classif as $key => $item) {
            $clas[$item['classificators']["id"]] = $item['classificators']["name"];
        }
        $clas[0] = 'Нет';
        ksort($clas);
        $this->set('classif', $clas);

        $istok = $this->Aunpaid->Source->find('list');
        $istok[0] = 'Нет';
        ksort($istok);
        $this->set('istok', $istok);

        $auth = $this->Auth->user('id');
        $this->set('auth', $auth);

        $type = "0";
        $this->set('type', $type);

		$this->set('confirm_permitted',$this->checkPermission('aunpaids','confirmutver'));
		
        $this->ajaxRender('edit');
        $this->render('edit');
    }

    public function edit($id = 0)
    {
        $this->Aunpaid->id = $id;
        $data = $this->Aunpaid->read();
        $this->request->data = $data;
//        if(!empty($data["Aunpaid"]["file_id"])){
//            $this->set('file_id',$data["Aunpaid"]["file_id"]);
//        }


        $organiz = $this->Aunpaid->Orgs->find('list', array('conditions' => array('orgs_group_id' => '99')));
        $organiz[0] = 'Нет';
        ksort($organiz);
        $this->set('organiz', $organiz);

        $contragents = $this->Aunpaid->Orgs->find('list', array('conditions' =>
            array('orgs_group_id' => $this->Aunpaid->Contragent->OrgsGroup->find('list', array('fields' => array('id', 'id'))))));
        $contragents[0] = 'Нет';
        ksort($contragents);
        $this->set('contragents', $contragents);

        $pay_types = $this->Aunpaid->PayType->find('list');
        $pay_types[0] = 'Нет';
        ksort($pay_types);
        $this->set('pay_types', $pay_types);

        $stati = $this->Aunpaid->Article->find('list', array('conditions' => array('type_id' => '2')));
        $stati[0] = 'Нет';
        ksort($stati);
        $this->set('stati', $stati);

        $classif = $this->Aunpaid->query("SELECT id, name FROM classificators WHERE type_id = 2");

        $clas = array();
        foreach ($classif as $key => $item) {
            $clas[$item['classificators']["id"]] = $item['classificators']["name"];
        }
        $clas[0] = 'Нет';
        ksort($clas);
        $this->set('classif', $clas);

        $istok = $this->Aunpaid->Source->find('list');
        $istok[0] = 'Нет';
        ksort($istok);
        $this->set('istok', $istok);

        $auth = $this->Auth->user('id');
        $this->set('auth', $auth);

        $type = "1";
        $this->set('type', $type);

		$this->set('confirm_permitted',$this->checkPermission('aunpaids','confirmutver'));
		
        $this->ajaxRender();
    }

    public function save()
    {
        if (isset($this->data['Aunpaid']) && !isset($this->data['cancel'])) {
            $due_date = new DateTime($this->data['Aunpaid']['due_date']);
            $this->request->data['Aunpaid']['due_date'] = $due_date->format('Y-m-d H:i:s');

            if (!empty($this->data['Aunpaid']['id'])) {
                $fl = $this->Aunpaid->query("SELECT file_id name FROM aunpaids WHERE id = " . $this->data['Aunpaid']['id']);
                $this->request->data['Aunpaid']['file_id'] = $fl[0]['aunpaids']['name'];
            }

            if (!empty($this->request->data['Aunpaid']['files'][0]['name'])) {
                $uploads_dir = '/app/webroot/files';
                $files = $this->request->data['Aunpaid']['files'];
                foreach ($files as $key => $file) {

                    $tmp_name = $file["name"];
                    $name = basename($tmp_name);
                    $url = rand(0, 14788999) . $name;
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . '/files/' . $url);
                    $fileSave[$key]["name"] = $file["name"];
                    $fileSave[$key]["url"] = $url;

                }
                $this->request->data['Aunpaid']['file_id'] = json_encode($fileSave);
                unset($this->request->data['Aunpaid']['files']);
            }


//            echo '<pre>'; print_r($this->request->data);echo '</pre>';
            if ($this->Aunpaid->saveAll($this->data)) {
                $id = $this->request->data['Aunpaid']['id'];
                if ($id == 0) $id = $this->Aunpaid->getLastInsertid();

                $user_id = $this->request->data['Aunpaid']['user_id'];
                $type = $this->request->data['Aunpaid']['type'];

                $data = array(
                    'type' => $type
                , 'aunpaid_id' => $id
                , 'user_id' => $user_id
                , 'modified' => date('Y-m-d H:i:s')
                );
                $this->Aunpaid->AunpaidHistory->save($data);

                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));
            } else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger'));
        }
//        debug($this->data);
        $this->redirect('view');
    }

    public function sendUtver($aunpaid_id)
    {
        $data = array('Aunpaid' => array(
            'id' => $aunpaid_id,
            'send_utver' => true,
            'utver' => false
        ));
        if ($this->Aunpaid->saveAll($data)) {

            $this->Aunpaid->id = $data['Aunpaid']['id'];
            $aunpaid = $this->Aunpaid->read();
            $user = $this->Auth->user('id');
            $this->Aunpaid->query("INSERT INTO `aunpaid_histories` (`user_id`, `aunpaid_id`, `modified`, `type`) VALUES ('" . $user . "','" . $aunpaid_id . "','" . date('Y-m-d H:i:s') . "','2')");

            $this->Session->setFlash('Заявка отправлена на утверждение.', 'default', array('class' => 'panel-success'));
        } else $this->Session->setFlash('Ошибка заявки!', 'default', array('class' => 'panel-danger'));
        $this->redirect('view/' . $data['Aunpaid']['id']);
    }

    public function backUtver($aunpaid_id)
    {
        $data = array('Aunpaid' => array(
            'id' => $aunpaid_id,
            'send_utver' => false,
            'utver' => false
        ));
        if ($this->Aunpaid->saveAll($data)) {

            $this->Aunpaid->id = $data['Aunpaid']['id'];

            $user = $this->Auth->user('id');
            $this->Aunpaid->query("INSERT INTO `aunpaid_histories` (`user_id`, `aunpaid_id`, `modified`, `type`) VALUES ('" . $user . "','" . $aunpaid_id . "','" . date('Y-m-d H:i:s') . "','3')");

            $this->Session->setFlash('Заявка отправлена назад.', 'default', array('class' => 'panel-success'));
        } else $this->Session->setFlash('Ошибка заявки!', 'default', array('class' => 'panel-danger'));
        $this->redirect('view/' . $data['Aunpaid']['id']);
    }

    public function confirmUtver($aunpaid_id)
    {
//        echo "<pre>";print_r($this);echo "</pre>";
        $data = array('Aunpaid' => array(
            'id' => $aunpaid_id,
            'send_utver' => false,
            'utver' => true
        ));
        if ($this->Aunpaid->saveAll($data)) {

            $this->Aunpaid->id = $data['Aunpaid']['id'];
            $aunpaid = $this->Aunpaid->read();

            $user = $this->Auth->user('id');
            $this->Aunpaid->query("INSERT INTO `aunpaid_histories` (`user_id`, `aunpaid_id`, `modified`, `type`) VALUES ('" . $user . "','" . $aunpaid_id . "','" . date('Y-m-d H:i:s') . "','4')");
            $this->Session->setFlash('Заявка утверждена.', 'default', array('class' => 'panel-success'));
        } else $this->Session->setFlash('Ошибка заявки!', 'default', array('class' => 'panel-danger'));
        $this->redirect('view/' . $data['Aunpaid']['id']);
    }

    public function delete($aunpaid_id = 0)
    {
        $this->Aunpaid->id = $aunpaid_id;
        $card = $this->Aunpaid->read();
        if ($card['Aunpaid']['utver'] == 0) {
            if ($this->Aunpaid->delete($aunpaid_id)) {

                $this->Aunpaid->query("DELETE FROM `aunpaid_histories` WHERE aunpaid_id =" . $aunpaid_id);

                $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));
            } else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));
        } else $this->Session->setFlash('Ошибка удаления - заявка уже утверждена!', 'default', array('class' => 'panel-danger'));
        $this->redirect('view/' . $card['Aunpaid']['id']);
    }

    public function on_money($aunpaid_id = 0)
    {
         $this->Aunpaid->id = $aunpaid_id;
        $datas = $this->Aunpaid->read();

        $data = array('Aunpaid' => array(
            'id' => $aunpaid_id,
            'paid' => true
        ));
      

         if($datas['Aunpaid']['utver'] == '0'){
            $this->Session->setFlash('Не прошёл утверждение!', 'default', array('class' => 'panel-danger'));

        }else{

            if ($this->Aunpaid->saveAll($data)) {

                $this->Aunpaid->id = $data['Aunpaid']['id'];
                $aunpaid = $this->Aunpaid->read();

                if (!empty($aunpaid["Aunpaid"]["id_bx"])) {
                    $postdata = http_build_query([
                        'AUTH_FORM' => 'Y',
                        'TYPE' => 'AUTH',
                        'USER_LOGIN' => 'finbot',
                        'USER_PASSWORD' => 'finbot',
                        'USER_REMEMBER' => 'Y'
                    ]);

                    $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));

                    $context = stream_context_create($opts);
                     file_get_contents('http://portal.stavtrack.ru/fin_close_task.php?ID=' . $aunpaid["Aunpaid"]["id_bx"], false, $context);
                    

                }
                $user = $this->Auth->user('id');
                $this->Aunpaid->query("INSERT INTO `aunpaid_histories` (`user_id`, `aunpaid_id`, `modified`, `type`) VALUES ('" . $user . "','" . $aunpaid_id . "','" . date('Y-m-d H:i:s') . "','5')");

                $this->Session->setFlash('Заявка оплачена.', 'default', array('class' => 'panel-success'));
            } else $this->Session->setFlash('Ошибка заявки!', 'default', array('class' => 'panel-danger'));
         }
        $this->redirect('view/' . $data['Aunpaid']['id']);
    }

    public function off_money($aunpaid_id = 0)
    {
        $data = array('Aunpaid' => array(
            'id' => $aunpaid_id,
            'paid' => false
        ));
        if ($this->Aunpaid->saveAll($data)) {

            $this->Aunpaid->id = $data['Aunpaid']['id'];
            $aunpaid = $this->Aunpaid->read();

            $this->Session->setFlash('Заявка не оплачена.', 'default', array('class' => 'panel-success'));
        } else $this->Session->setFlash('Ошибка заявки!', 'default', array('class' => 'panel-danger'));
        $this->redirect('view/' . $data['Aunpaid']['id']);
    }

    public function view_history($id = 0)
    {
        $user = $this->Aunpaid->User->find('list', array('fields' => array('id', 'username')));
        $this->set('user', $user);

        /**
         * 1 - создал
         * 2 - изменил
         * 3 - отправил на утверж
         * 4 - вернул из утв
         * 5 - утвердил
         */
        $type = ['Создал', 'Изменил', 'Отправил на утверждение', 'Вернул из утверждения', 'Утвердил', 'Оплачено'];
        $this->set('type', $type);

        $this->Aunpaid->id = $id;
        $data = $this->Aunpaid->read();
        $this->request->data = $data;

        $this->ajaxRender();
    }

    public function checkdate($string)
    {
        $result = preg_match( '~^0?(\d|[0-2]\d|3[0-2])\.0?(\d|1[0-2])\.(\d{4})$~',$string,$matches);
        return !!$result;
    }

    public function get_search_conditions($search_text = null)
    {
        if (is_int($search_text)) {
            $conditions = array(
                'OR' => array(
                    'Aunpaid.money LIKE "%' . $search_text . '%"'
                )
            );
        } elseif ($this->checkdate($search_text)) {
            $conditions = array(
                'OR' => array(
                    'DATE_FORMAT(Aunpaid.due_date, "%d.%m.%Y") LIKE "%' . $search_text . '%"'
                , 'DATE_FORMAT(Aunpaid.date, "%d.%m.%Y") LIKE "%' . $search_text . '%"'
                )
            );
        } else {
            $conditions = array(
                'OR' => array(
                    'Orgs.name LIKE "%' . $search_text . '%"'
                , 'Contragent.name LIKE "%' . $search_text . '%"'
                , 'PayType.name LIKE "%' . $search_text . '%"'
                , 'Aunpaid.note LIKE "%' . $search_text . '%"'
                , 'Source.name LIKE "%' . $search_text . '%"'
                )
            );
        }
        return $conditions;
    }
}

?>